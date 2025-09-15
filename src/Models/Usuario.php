<?php

namespace App\Models;

use Laudis\Neo4j\ClientBuilder;

class Usuario
{
    private $client;

    public function __construct()
    {
        $uri = 'neo4j://localhost:7687';
        $user = 'neo4j';
        $password = 'password';

        $this->client = ClientBuilder::create()
            ->withDriver('default', $uri, \Laudis\Neo4j\Neo4j\Authentication\Authenticate::basic($user, $password))
            ->build();
    }

    /**
     * @param array $dados - Um array com 'nome', 'email', e 'senha'.
     * @return bool - Retorna true se foi bem-sucedido, false se não
     */
    public function create(array $dados)
    {
        $senhaHash = password_hash($dados['senha'], PASSWORD_DEFAULT);

        $query = 'CREATE (n:Usuario {nome: $nome, email: $email, senha: $senha, data_cadastro: datetime(), admin: false})';

        try {
            $this->client->run($query, [
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'senha' => $senhaHash
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Encontra um usuário pelo seu email.
     * @param string $email - O email a ser procurado.
     * @return \Laudis\Neo4j\Neo4j\Databags\Record|null - Retorna o registro do usuário ou null se não encontrar.
     */
    public function findByEmail(string $email)
    {
        $query = 'MATCH (n:Usuario) WHERE m.email = $email RETURN n LIMIT 1';
        $result = $this->client->run($query, ['email' => $email]);
        return $result->first();
    }

}