<?php

namespace App\Models;

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Authentication\Authenticate;

class Tag
{
    private $client;

    public function __construct()
    {
        $uri = 'neo4j://127.0.0.1:7687';
        $user = 'neo4j';
        $password = 'password';

        $this->client = ClientBuilder::create()
            ->withDriver('default', $uri, \Laudis\Neo4j\Authentication\Authenticate::basic($user, $password))
            ->build();
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