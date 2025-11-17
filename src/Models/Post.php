<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;

class Post
{
    private $client;

    public function __construct()
    {
        $uri = $_ENV['NEO4J_URI'];
        $user = $_ENV['NEO4J_USER'];
        $password = $_ENV['NEO4J_PASS'];

        $this->client = ClientBuilder::create()
            ->withDriver('default', $uri, Authenticate::basic($user, $password))
            ->build();
    }

    /**
     * Busca todos os posts e seus autores para exibir no feed.
     * @return array - Uma lista de posts.
     */
// Em src/Models/Post.php

    public function findAll(?int $id_usuario_logado = null, ?array $tag_ids = null): array
    {
        $posts = [];
        try {
            // Se há filtro de tags, faz a query diferente
            if (!empty($tag_ids)) {
                $query = '
                OPTIONAL MATCH (current_user:Usuario WHERE id(current_user) = $id_usuario_logado)
                MATCH (author:Usuario)-[:POSTED]->(p:Post)
                MATCH (p)-[:HAS_TAG]->(t:Tag)
                WHERE id(t) IN $tag_ids
                OPTIONAL MATCH (voter:Usuario)-[:VOTED_UP]->(p)
                
                WITH p, author, count(voter) AS upvotes, current_user, collect(t.nome) AS tags
                ORDER BY p.data_criacao DESC
                
                RETURN 
                    p.titulo AS titulo,
                    p.conteudo AS conteudo,
                    p.codigo AS codigo,
                    p.linguagem AS linguagem,
                    p.imagem_url AS imagem_url,
                    p.data_criacao AS data_criacao,
                    author.nome AS autor,
                    id(p) AS id,
                    upvotes,
                    CASE WHEN current_user IS NOT NULL THEN EXISTS((current_user)-[:VOTED_UP]->(p)) ELSE false END AS user_has_voted,
                    tags
            ';

                $result = $this->client->run($query, [
                    'id_usuario_logado' => $id_usuario_logado,
                    'tag_ids' => $tag_ids
                ]);
            } else {
                // Query sem filtro de tags
                $query = '
                OPTIONAL MATCH (current_user:Usuario WHERE id(current_user) = $id_usuario_logado)
                MATCH (author:Usuario)-[:POSTED]->(p:Post)
                OPTIONAL MATCH (p)-[:HAS_TAG]->(t:Tag)
                OPTIONAL MATCH (voter:Usuario)-[:VOTED_UP]->(p)
                
                WITH p, author, count(voter) AS upvotes, current_user, collect(t.nome) AS tags
                ORDER BY p.data_criacao DESC
                
                RETURN 
                    p.titulo AS titulo,
                    p.conteudo AS conteudo,
                    p.codigo AS codigo,
                    p.linguagem AS linguagem,
                    p.imagem_url AS imagem_url,
                    p.data_criacao AS data_criacao,
                    author.nome AS autor,
                    id(p) AS id,
                    upvotes,
                    CASE WHEN current_user IS NOT NULL THEN EXISTS((current_user)-[:VOTED_UP]->(p)) ELSE false END AS user_has_voted,
                    tags
            ';

                $result = $this->client->run($query, [
                    'id_usuario_logado' => $id_usuario_logado
                ]);
            }

            foreach ($result as $record) {
                $posts[] = $record->toArray();
            }

        } catch (\Exception $e) {
            error_log("Erro ao buscar posts: " . $e->getMessage());
        }

        return $posts;
    }

    // Adicione este método dentro da classe App\Models\Post

    /**
     * Busca um único post pelo seu ID, juntamente com o nome do autor.
     * @param int $id_post O ID do post a ser procurado.
     * @return array|null Retorna os dados do post como um array associativo ou null se não encontrar.
     */
    public function findById(int $id_post, ?int $id_usuario_logado = null): ?array
    {
        try {
            $query = '
            OPTIONAL MATCH (current_user:Usuario WHERE id(current_user) = $id_usuario_logado)
            MATCH (author:Usuario)-[:POSTED]->(p:Post WHERE id(p) = $id_post)
            OPTIONAL MATCH (p)-[:HAS_TAG]->(t:Tag)
            OPTIONAL MATCH (voter:Usuario)-[:VOTED_UP]->(p)
            
            RETURN 
                p.titulo AS titulo,
                p.conteudo AS conteudo,
                p.codigo AS codigo,
                p.linguagem AS linguagem,
                p.imagem_url AS imagem_url,
                p.data_criacao AS data_criacao,
                author.nome AS autor,
                id(p) AS id,
                count(voter) AS upvotes,
                CASE WHEN current_user IS NOT NULL THEN EXISTS((current_user)-[:VOTED_UP]->(p)) ELSE false END AS user_has_voted,
                collect(t.nome) AS tags
            LIMIT 1
        ';

            $result = $this->client->run($query, [
                'id_post' => $id_post,
                'id_usuario_logado' => $id_usuario_logado
            ]);

            if ($result->isEmpty()) {
                return null;
            }

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
        CALL {
            MATCH (u:Usuario)-[:POSTED]->(p:Post)
            RETURN
                u.nome AS autor,
                "criou o post:" AS tipo_evento,
                p.titulo AS titulo_alvo,
                id(p) AS id_alvo,
                p.data_criacao AS data_evento
                
            UNION
            
            MATCH (u:Usuario)-[:COMENTOU]->(c:Comentario)-[:É_RESPOSTA_DE]->(p:Post)
            RETURN
                u.nome AS autor,
                "comentou em:" AS tipo_evento,
                p.titulo AS titulo_alvo,
                id(p) AS id_alvo,
                c.data_criacao AS data_evento
                
            UNION
            
            MATCH (u:Usuario)-[r:VOTED_UP]->(p:Post)
            RETURN
                u.nome AS autor,
                "deu um upvote em:" AS tipo_evento,
                p.titulo AS titulo_alvo,
                id(p) AS id_alvo,
                r.created_at AS data_evento
        }
        RETURN autor, tipo_evento, titulo_alvo, id_alvo, data_evento
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
            // Verifica se já votou
            $queryCheck = '
            MATCH (u:Usuario)-[r:VOTED_UP]->(p:Post)
            WHERE id(u) = $id_usuario AND id(p) = $id_post
            RETURN r
        ';

            $result = $this->client->run($queryCheck, [
                'id_usuario' => $id_usuario,
                'id_post' => $id_post
            ]);

            if ($result->isEmpty()) {
                // Se não votou, cria o voto
                $queryCreate = '
                MATCH (u:Usuario WHERE id(u) = $id_usuario)
                MATCH (p:Post WHERE id(p) = $id_post)
                CREATE (u)-[r:VOTED_UP {created_at: timestamp()}]->(p)
            ';

                $this->client->run($queryCreate, [
                    'id_usuario' => $id_usuario,
                    'id_post' => $id_post
                ]);
            } else {
                // Se já votou, remove o voto
                $queryDelete = '
                MATCH (u:Usuario)-[r:VOTED_UP]->(p:Post)
                WHERE id(u) = $id_usuario AND id(p) = $id_post
                DELETE r
            ';

                $this->client->run($queryDelete, [
                    'id_usuario' => $id_usuario,
                    'id_post' => $id_post
                ]);
            }

            return true;

        } catch (\Exception $e) {
            error_log("Erro no toggleVote: " . $e->getMessage());
            return false;
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
            // Validar dados obrigatórios
            if (empty($dados['titulo']) || empty($dados['conteudo']) || empty($dados['id_usuario'])) {
                throw new \Exception('Título, conteúdo e usuário são obrigatórios');
            }

            // Criar o post com todos os campos novos
            $query = '
                MATCH (u:Usuario WHERE id(u) = $id_usuario)
                CREATE (u)-[:POSTED]->(p:Post {
                    titulo: $titulo,
                    conteudo: $conteudo,
                    codigo: $codigo,
                    linguagem: $linguagem,
                    imagem_url: $imagem_url,
                    data_criacao: timestamp(),
                    upvotes: 0
                })
                RETURN id(p) AS id_post
            ';

            $result = $this->client->run($query, [
                'id_usuario' => (int)$dados['id_usuario'],
                'titulo' => htmlspecialchars($dados['titulo']),
                'conteudo' => htmlspecialchars($dados['conteudo']),
                'codigo' => htmlspecialchars($dados['codigo'] ?? ''),
                'linguagem' => $dados['linguagem'] ?? 'javascript',
                'imagem_url' => $dados['imagem_url'] ?? null
            ]);

            if ($result->isEmpty()) {
                return null;
            }

            $id_post = $result->first()->get('id_post');

            // Associar tags ao post (se houver)
            if (!empty($dados['tag_ids']) && is_array($dados['tag_ids'])) {
                foreach ($dados['tag_ids'] as $id_tag) {
                    $this->associarTag($id_post, (int)$id_tag);
                }
            }

            return $id_post;

        } catch (\Exception $e) {
            error_log("Erro ao criar post: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Associa uma tag a um post
     */
    public function associarTag(int $id_post, int $id_tag): bool
    {
        try {
            $query = '
                MATCH (p:Post WHERE id(p) = $id_post)
                MATCH (t:Tag WHERE id(t) = $id_tag)
                MERGE (p)-[:HAS_TAG]->(t)
            ';

            $this->client->run($query, [
                'id_post' => $id_post,
                'id_tag' => $id_tag
            ]);

            return true;
        } catch (\Exception $e) {
            error_log("Erro ao associar tag: " . $e->getMessage());
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
            MATCH (u:Usuario)-[:POSTED]->(p:Post)
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
