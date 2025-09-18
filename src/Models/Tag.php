<?php

namespace App\Models;

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
            $uri = 'bolt://127.0.0.1:7687';
            $user = 'neo4j';
            $password = 'password'; // Garanta que esta é a senha do seu Neo4j Desktop

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
    public function findAll(): array
    {
        $query = 'MATCH (t:Tag) RETURN t.nome AS nome ORDER BY t.nome';
        $result = $this->client->run($query);
        $tags = [];
        foreach ($result as $record) {
            $tags[] = $record->get('nome');
        }
        return $tags;
    }

}