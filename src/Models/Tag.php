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

}