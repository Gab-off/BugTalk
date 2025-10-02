<?php

namespace App\Models;

use Laudis\Neo4j\ClientBuilder;

class Usuario
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
        $query = 'MATCH (n:Usuario) WHERE n.email = $email RETURN n.senha AS senha, id(n) AS id, n.nome AS nome, n.admin AS isAdmin LIMIT 1';;
        $result = $this->client->run($query, ['email' => $email]);

        if ($result->isEmpty()) {
            return null;
        }

        return $result->first();
    }

    // Dentro da classe App\Models\Usuario

    /**
     * Encontra um usuário pelo seu ID interno do Neo4j.
     * @param int $id_usuario O ID do usuário a ser procurado.
     * @return array|null Retorna os dados do usuário como um array associativo ou null se não encontrar.
     */
    public function findById(int $id_usuario): ?array
    {
        try {
            $query = '
            MATCH (u:Usuario) 
            WHERE id(u) = $id_usuario 
            RETURN u.nome AS nome, u.email AS email, id(u) AS id
            LIMIT 1
        ';
            $result = $this->client->run($query, ['id_usuario' => $id_usuario]);

            if ($result->isEmpty()) {
                return null; // Usuário não encontrado
            }

            return $result->first()->toArray(); // Retorna o usuário como um array associativo

        } catch (\Exception $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return null;
        }
    }

    // Dentro da classe App\Models\Usuario

    /**
     * Busca todos os usuários e a contagem de posts de cada um para a área de admin.
     * @return array
     */
    public function findAllWithPostCount(): array
    {
        $usuarios = [];
        try {
            // Esta query usa OPTIONAL MATCH, que busca por posts, mas não falha se um usuário não tiver nenhum.
            $query = '
            MATCH (u:Usuario)
            OPTIONAL MATCH (u)-[:POSTED]->(p:POST)
            RETURN u.nome AS nome, u.email AS email, id(u) AS id, count(p) AS postCount
            ORDER BY u.nome ASC
        ';
            $result = $this->client->run($query);

            foreach ($result as $record) {
                $usuarios[] = $record->toArray();
            }
        } catch (\Exception $e) {
            // Em um app real, é bom registrar o erro em um arquivo de log
            error_log("Erro ao buscar usuários com contagem de posts: " . $e->getMessage());
        }
        return $usuarios;
    }

}