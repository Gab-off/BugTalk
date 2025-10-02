<?php

namespace App\Models;

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Authentication\Authenticate;

class Post
{
    private $client;

    public function __construct()
    {
        $uri = 'neo4j://127.0.0.1:7687';
        $user = 'neo4j';
        $password = 'password';

        $this->client = ClientBuilder::create()
            ->withDriver('default', $uri, Authenticate::basic($user, $password))
            ->build();
    }

    /**
     * Busca todos os posts e seus autores para exibir no feed.
     * @return array - Uma lista de posts.
     */
// Em src/Models/Post.php

    public function findAll(?int $id_usuario_logado = null): array
    {
        $posts = [];
        try {
            // Esta é a sua query, agora com a adição da busca por tags
            $query = '
            // Encontra o usuário logado, se houver
            OPTIONAL MATCH (currentUser:Usuario) WHERE id(currentUser) = $id_usuario_logado
            
            // Encontra o padrão de posts e autores
            MATCH (author:Usuario)-[:POSTED]->(p:POST)
            
            // --- ADIÇÃO: Encontra as tags de cada post ---
            OPTIONAL MATCH (p)-[:HAS_TAG]->(t:Tag)
            
            // Encontra os votos para cada post
            OPTIONAL MATCH (voter:Usuario)-[:VOTED_UP]->(p)
            
            // Agrupamos os resultados
            WITH p, author, count(voter) AS upvotes, currentUser, collect(t.nome) AS tags
            
            // Ordenamos pelo mais recente
            ORDER BY p.data_criacao DESC
            
            // Finalmente, retornamos tudo o que precisamos
            RETURN 
                p.titulo AS titulo, 
                p.conteudo AS conteudo, 
                author.nome AS autor, 
                id(p) AS id,
                upvotes,
                // A verificação se o usuário já votou
                CASE WHEN currentUser IS NOT NULL THEN EXISTS((currentUser)-[:VOTED_UP]->(p)) ELSE false END AS userHasVoted,
                // --- ADIÇÃO: Retornamos a lista de nomes das tags ---
                tags
        ';

            $result = $this->client->run($query, ['id_usuario_logado' => $id_usuario_logado]);

            foreach ($result as $record) {
                $posts[] = $record->toArray();
            }
        } catch (\Exception $e) {
            die("ERRO AO BUSCAR POSTS no findAll(): " . $e->getMessage());
        }
        return $posts;
    }

    // Adicione este método dentro da classe App\Models\Post

    /**
     * Busca um único post pelo seu ID, juntamente com o nome do autor.
     * @param int $id_post O ID do post a ser procurado.
     * @return array|null Retorna os dados do post como um array associativo ou null se não encontrar.
     */
    public function findById(int $id_post): ?array
    {
        try {
            // Query que busca o post e seu autor pelo ID do post
            $query = '
            MATCH (author:Usuario)-[:POSTED]->(p:POST)
            WHERE id(p) = $id_post
            RETURN
                p.titulo AS titulo,
                p.conteudo AS conteudo,
                author.nome AS autor,
                id(p) AS id
            LIMIT 1
        ';
            $result = $this->client->run($query, ['id_post' => $id_post]);

            // Verifica de forma segura se o post foi encontrado
            if ($result->isEmpty()) {
                return null;
            }

            // Retorna os dados do post como um array associativo
            return $result->first()->toArray();

        } catch (\Exception $e) {
            error_log("Erro ao buscar post por ID: " . $e->getMessage());
            return null;
        }
    }

    public function getRecentActivity(int $limit = 5)
    {
        $activities = [];
        try {
            $query = '
            MATCH (u:Usuario)-[:POSTED]->(p:POST)
            RETURN
                u.nome AS autor,
                "criou o post:" AS tipo_evento,
                p.titulo AS titulo_alvo,
                id(p) AS id_alvo,
                p.data_criacao AS data_evento
                
            UNION
            
            MATCH (u:Usuario)-[:COMENTOU]->(c:Comentario)-[:É_RESPOSTA_DE]->(p:POST)
            RETURN
                u.nome AS autor,
                "comentou em:" AS tipo_evento,
                p.titulo AS titulo_alvo,
                id(p) AS id_alvo,
                c.data_criacao AS data_evento
                
            UNION
            
            MATCH (u:Usuario)-[r:VOTED_UP]->(p:POST)
            RETURN
                u.nome AS autor,
                "deu um upvote em:" AS tipo_evento,
                p.titulo AS titulo_alvo,
                id(p) AS id_alvo,
                r.created_at AS data_evento
                            
            ORDER BY data_evento DESC
            LIMIT $limit
            ';
            $result = $this->client->run($query, ['limit' => $limit]);
            foreach ($result as $record) {
                $activities[] = $record->toArray();
            }
        } catch (\Exception $e) {
            error_log("Erro ao buscar atividades: " . $e->getMessage());
        }
        return $activities;
    }

    /**
     * Adiciona ou remove um upvote de um usuário em um post.
     * @param int $id_post - O ID do post a ser votado.
     * @param int $id_usuario - O ID do usuário que está votando.
     * @return bool - Retorna true se a operação foi bem-sucedida.
     */
    public function toggleVote(int $id_post, int $id_usuario): bool
    {
        try {
            $queryCheck = '
                MATCH (u:Usuario)-[r:VOTED_UP]->(p:POST)
                WHERE id(u) = $id_usuario AND id(p) = $id_post
                RETURN r
            ';
            $result = $this->client->run($queryCheck, ['id_usuario' => $id_usuario, 'id_post' => $id_post]);

            if ($result->isEmpty()) {
                $queryCreate = '
                MATCH (u:Usuario) WHERE id(u) = $id_usuario
                MATCH (p:POST) WHERE id(p) = $id_post
                CREATE (u)-[r:VOTED_UP {created_at: datetime()}]->(p)
                ';
                $this->client->run($queryCreate, ['id_usuario' => $id_usuario, 'id_post' => $id_post]);
            } else {
                $queryDelete = '
                    MATCH (u:Usuario)-[r:VOTED_UP]->(p:POST)
                    WHERE id(u) = $id_usuario AND id(p) = $id_post
                    DELETE r
                ';
                $this->client->run($queryDelete, ['id_usuario' => $id_usuario, 'id_post' => $id_post]);
            }
            return true;
        } catch (\Exception $e) {
            die("ERRO DENTRO DO toggleVote(): " . $e->getMessage());
        }
    }


    /**
     * Cria um novo post no banco de dados, já conectado ao seu autor.
     * @param array $dados - Deve conter 'titulo', 'conteudo' e 'id_usuario'.
     * @return int|null - Retorna o ID do post em caso de sucesso, ou null se falhar.
     */
    public function create(array $dados): ?int
    {
        try {
            $query = '
            MATCH (u:Usuario) WHERE id(u) = $id_usuario
            CREATE (u)-[:POSTED]->(p:POST {
                titulo: $titulo,
                conteudo: $conteudo,
                data_criacao: datetime(),
                upvotes: 0
            })
            RETURN id(p) AS id_post
        ';
            $result = $this->client->run($query, [
                'id_usuario' => $dados['id_usuario'],
                'titulo' => htmlspecialchars($dados['titulo']),
                'conteudo' => htmlspecialchars($dados['conteudo'])
            ]);

            return $result->first()->get('id_post');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * NOVO MÉTODO: Cria o relacionamento [:HAS_TAG] entre um post e uma tag.
     * @param int $id_post - O ID do post que receberá a tag.
     * @param int $id_tag - O ID da tag a ser associada.
     * @return bool - Retorna true em caso de sucesso.
     */
    public function associarTag(int $id_post, int $id_tag): bool
    {
        try {
            $query = '
                MATCH (p:POST) WHERE id(p) = $id_post
                MATCH (t:Tag) WHERE id(t) = $id_tag
                MERGE (p)-[:HAS_TAG]->(t)
            ';
            $this->client->run($query, ['id_post' => $id_post, 'id_tag' => $id_tag]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Dentro da classe App\Models\Post

    /**
     * Busca todos os posts criados por um ID de usuário específico.
     * @param int $id_usuario
     * @return array
     */
    public function findByUsuarioId(int $id_usuario): array
    {
        $posts = [];
        try {
            $query = '
            MATCH (u:Usuario)-[:POSTED]->(p:POST)
            WHERE id(u) = $id_usuario
            RETURN p.titulo AS titulo, p.conteudo AS conteudo, p.data_criacao AS data, id(p) AS id
            ORDER BY p.data_criacao DESC
        ';
            $result = $this->client->run($query, ['id_usuario' => $id_usuario]);

            foreach ($result as $record) {
                $posts[] = $record->toArray();
            }
        } catch (\Exception $e) {
            error_log("Erro ao buscar posts por usuário: " . $e->getMessage());
        }
        return $posts;
    }

}
