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
    public function findAll(): array
    {
        $posts = [];
        try {
            $query = '
                MATCH (u:Usuario)-[:POSTED]->(p:POST)
                RETURN p.titulo AS titulo, p.conteudo AS conteudo, u.nome AS autor, id(p) AS id
                ORDER BY p.data_criacao DESC
            ';
            $result = $this->client->run($query);
            foreach ($result as $record) {
                $posts[] = $record->toArray();
            }
        } catch (\Exception $e) {

        }
        return $posts;
    }

    /**
     * Cria um novo post no banco de dados, já conectado ao seu autor.
     * @param array $dados - Deve conter 'titulo', 'conteudo' e 'id_usuario'.
     * @return bool - Retorna true em caso de sucesso, false se falhar.
     */
    public function create(array $dados): bool
    {
        try {
            $query = '
            MATCH (u:Usuario) WHERE id(u) = $id_usuario
            CREATE (u)-[:POSTED]->(p:POST {
                titulo: $titulo,
                conteudo: $conteudo,
                data_criacao: datetime(),
                score: 0,
                likes: 0
            })
        ';
            $this->client->run($query, [
                'id_usuario' => $dados['id_usuario'],
                'titulo' => $dados['titulo'],
                'conteudo' => $dados['conteudo']
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
