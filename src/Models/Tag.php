<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
// Importando as classes no topo (melhor prática)
use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Authentication\Authenticate;

class Tag
{
    private $client;

    public function __construct()
    {
        try {
            // Tenta se conectar. Usar bolt:// é mais direto para conexões locais.
            $uri = $_ENV['NEO4J_URI'];
            $user = $_ENV['NEO4J_USER'];
            $password = $_ENV['NEO4J_PASS']; // Garanta que esta é a senha do seu Neo4j Desktop

            $this->client = ClientBuilder::create()
                ->withDriver('default', $uri, Authenticate::basic($user, $password))
                ->build();

        } catch (\Exception $e) {
            // Se a conexão falhar, o script para de forma controlada
            // e mostra uma mensagem útil em vez de quebrar a página.
            // Em um ambiente de produção, você logaria o erro em um arquivo.
            die("ERRO DE CONEXÃO COM O BANCO DE DADOS: " . $e->getMessage());
        }
    }

    /**
     * Encontra uma tag pelo nome. Se não existir, cria.
     * @param string $nomeTag O nome da tag (ex: "php")
     * @return int O ID da tag encontrada ou criada.
     */
    public function findOrCreateByName(string $nomeTag): int
    {
        $query = 'MERGE (t:Tag {nome: $nome}) RETURN id(t) AS id';
        $result = $this->client->run($query, ['nome' => $nomeTag]);
        return $result->first()->get('id');
    }

    /**
     * Busca todas as tags existentes no banco para listar na sidebar.
     * @return array
     */
    public function findAll(): array {
        $query = 'MATCH (t:Tag) RETURN id(t) AS id, t.nome AS nome ORDER BY t.nome';
        $result = $this->client->run($query);

        $tags = [];
        foreach ($result as $record) {
            $tags[] = [
                'id' => $record->get('id'),
                'nome' => $record->get('nome')
            ];
        }
        return $tags;
    }

}