<?php
namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
use Laudis\Neo4j\Authentication\Authenticate;
use Laudis\Neo4j\ClientBuilder;

class Comentario {
    private $client;

    public function __construct() {
        // Sua lógica de conexão
        $uri = $_ENV['NEO4J_URI'];
        $user = $_ENV['NEO4J_USER'];
        $password = $_ENV['NEO4J_PASS'];
        $this->client = ClientBuilder::create()->withDriver('default', $uri, Authenticate::basic($user, $password))->build();
    }

    /**
     * Cria um novo comentário.
     * @param array $dados com 'texto', 'id_usuario', 'id_pai' (ID do post ou de outro comentário)
     * @param string $tipoPai 'POST' ou 'Comentario'
     * @return bool
     */
    public function create(array $dados, string $tipoPai): bool {
        try {
            // Esta query encontra o usuário e o "pai" (Post ou Comentário)
            // e cria o novo comentário com os relacionamentos corretos.
            $query = '
                MATCH (autor:Usuario) WHERE id(autor) = $id_usuario
                MATCH (pai) WHERE id(pai) = $id_pai AND ($tipoPai IN labels(pai))
                CREATE (autor)-[:COMENTOU]->(c:Comentario {
                    texto: $texto,
                    data_criacao: datetime()
                })-[:É_RESPOSTA_DE]->(pai)
            ';
            $this->client->run($query, [
                'id_usuario' => $dados['id_usuario'],
                'id_pai' => $dados['id_pai'],
                'tipoPai' => $tipoPai,
                'texto' => htmlspecialchars($dados['texto'])
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Erro ao criar comentário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os comentários de um post e os organiza de forma aninhada.
     * @param int $id_post
     * @return array
     */
    public function findComentariosAninhados(int $id_post): array {
        $comentarios = [];
        try {
            // Query para buscar todos os comentários de um post, seus autores, e o ID de quem eles respondem.
            $query = '
                MATCH (p:POST)<-[:É_RESPOSTA_DE*]-(c:Comentario)<-[:COMENTOU]-(u:Usuario)
                WHERE id(p) = $id_post
                OPTIONAL MATCH (c)-[:É_RESPOSTA_DE]->(pai)
                RETURN 
                    id(c) AS id, 
                    c.texto AS texto, 
                    u.nome AS autor, 
                    id(pai) AS id_pai
            ';
            $result = $this->client->run($query, ['id_post' => $id_post]);

            $todosComentarios = [];
            foreach ($result as $record) {
                $todosComentarios[$record->get('id')] = $record->toArray();
            }

            // Mágica da organização: transforma a lista plana em uma árvore aninhada
            $comentariosAninhados = [];
            foreach ($todosComentarios as $id => &$comentario) {
                if (is_null($comentario['id_pai']) || !isset($todosComentarios[$comentario['id_pai']])) {
                    $comentariosAninhados[] = &$comentario;
                } else {
                    $todosComentarios[$comentario['id_pai']]['respostas'][] = &$comentario;
                }
            }
            return $comentariosAninhados;

        } catch (\Exception $e) {
            error_log("Erro ao buscar comentários: " . $e->getMessage());
            return [];
        }
    }
}