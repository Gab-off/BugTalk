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
        $uri = $_ENV['NEO4J_URI'];
        $user = $_ENV['NEO4J_USER'];
        $password = $_ENV['NEO4J_PASS'];

        $this->client = ClientBuilder::create()
            ->withDriver('default', $uri, Authenticate::basic($user, $password))
            ->build();
    }

    /**
     * Cria um novo comentário
     */
    public function create(array $dados, string $tipo_pai): bool {
        try {
            $query = '
                MATCH (autor:Usuario WHERE id(autor) = $id_usuario)
                MATCH (pai WHERE id(pai) = $id_pai AND $tipo_pai IN labels(pai))
                CREATE (autor)-[:COMENTOU]->(c:Comentario {
                    texto: $texto,
                    data_criacao: timestamp()
                })-[:É_RESPOSTA_DE]->(pai)
            ';

            $this->client->run($query, [
                'id_usuario' => (int)$dados['id_usuario'],
                'id_pai' => (int)$dados['id_pai'],
                'tipo_pai' => $tipo_pai,
                'texto' => htmlspecialchars($dados['texto'])
            ]);

            return true;
        } catch (\Exception $e) {
            error_log("Erro ao criar comentário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os comentários de um post organizados de forma aninhada (incluindo respostas de respostas)
     */
    public function findComentariosAninhados(int $id_post): array {
        try {
            // Busca comentários DIRETOS do post
            $query = '
                MATCH (p:Post WHERE id(p) = $id_post)<-[:É_RESPOSTA_DE]-(c:Comentario)<-[:COMENTOU]-(u:Usuario)
                RETURN 
                    id(c) AS id,
                    c.texto AS texto,
                    u.nome AS autor,
                    c.data_criacao AS data_criacao
                ORDER BY c.data_criacao ASC
            ';

            $result = $this->client->run($query, ['id_post' => $id_post]);

            $comentarios = [];
            foreach ($result as $record) {
                $comentario_id = $record->get('id');
                $comentarios[] = [
                    'id' => $comentario_id,
                    'texto' => $record->get('texto'),
                    'autor' => $record->get('autor'),
                    'data_criacao' => $record->get('data_criacao'),
                    'respostas' => $this->buscarRespostasRecursivas($comentario_id)
                ];
            }

            return $comentarios;

        } catch (\Exception $e) {
            error_log("Erro ao buscar comentários: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca respostas recursivas de um comentário (e respostas de respostas)
     */
    private function buscarRespostasRecursivas(int $id_comentario): array {
        try {
            $query = '
                MATCH (c:Comentario WHERE id(c) = $id_comentario)<-[:É_RESPOSTA_DE]-(r:Comentario)<-[:COMENTOU]-(u:Usuario)
                RETURN 
                    id(r) AS id,
                    r.texto AS texto,
                    u.nome AS autor,
                    r.data_criacao AS data_criacao
                ORDER BY r.data_criacao ASC
            ';

            $result = $this->client->run($query, ['id_comentario' => $id_comentario]);

            $respostas = [];
            foreach ($result as $record) {
                $resposta_id = $record->get('id');
                $respostas[] = [
                    'id' => $resposta_id,
                    'texto' => $record->get('texto'),
                    'autor' => $record->get('autor'),
                    'data_criacao' => $record->get('data_criacao'),
                    'respostas' => $this->buscarRespostasRecursivas($resposta_id)  // ← RECURSÃO
                ];
            }

            return $respostas;

        } catch (\Exception $e) {
            error_log("Erro ao buscar respostas: " . $e->getMessage());
            return [];
        }
    }
}
?>
