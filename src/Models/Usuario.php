<?php

namespace App\Models;

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
use Laudis\Neo4j\ClientBuilder;

class Usuario
{
    private $client;

    public function __construct()
    {
        $uri = $_ENV['NEO4J_URI'];
        $user = $_ENV['NEO4J_USER'];
        $password = $_ENV['NEO4J_PASS'];

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

        // Inicializando todas as propriedades necessárias, incluindo banned e timeoutUntil
        $query = 'CREATE (n:Usuario {nome: $nome, email: $email, senha: $senha, data_cadastro: datetime(), isAdmin: false, banned: false})';

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
        $query = 'MATCH (n:Usuario) WHERE n.email = $email RETURN   n, 
                                                                    n.senha AS senha, 
                                                                    id(n) AS id, 
                                                                    n.nome AS nome, 
                                                                    COALESCE(n.isAdmin, false) AS isAdmin,
                                                                    COALESCE(n.banned, false) AS banned,
                                                                    n.timeoutUntil AS timeoutUntil LIMIT 1';
        $result = $this->client->run($query, ['email' => $email]);

        if ($result->isEmpty()) {
            return null;
        }

        return $result->first();
    }

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
            RETURN u.nome AS nome, u.email AS email, id(u) AS id, u.isAdmin AS isAdmin
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
            OPTIONAL MATCH (u)-[:POSTED]->(p:Post)
            RETURN  u.nome AS nome, 
                    u.email AS email, 
                    id(u) AS id, 
                    count(p) AS postCount, 
                    u.banned AS isBanned, 
                    u.timeoutUntil AS timeoutUntil
            ORDER BY u.nome ASC
        ';
            $result = $this->client->run($query);

            foreach ($result as $record) {
                $usuarios[] = $record->toArray();
            }
        } catch (\Exception $e) {
            error_log("Erro ao buscar usuários com contagem de posts: " . $e->getMessage());
        }
        return $usuarios;
    }

    /**
     * Aplica um timeout em um usuário por uma duração específica.
     * @param int $id_usuario O ID do usuário.
     * @param string $duration Uma string de intervalo de tempo (ex: 'P1D' para 1 dia, 'PT1H' para 1 hora).
     * @return bool
     */
    public function timeout(int $id_usuario, string $duration = 'P1D'): bool
    {
        try {
            $query = '
            MATCH (u:Usuario) WHERE id(u) = $id_usuario
            SET u.timeoutUntil = datetime() + duration($duration)
        ';
            $this->client->run($query, [
                'id_usuario' => $id_usuario,
                'duration' => $duration
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("Erro ao aplicar timeout: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bane permanentemente um usuário.
     * @param int $id_usuario
     * @return bool
     */
    public function ban(int $id_usuario): bool
    {
        try {
            $query = 'MATCH (u:Usuario) WHERE id(u) = $id_usuario SET u.banned = true';
            $this->client->run($query, ['id_usuario' => $id_usuario]);
            return true;
        } catch (\Exception $e) {
            error_log("Erro ao banir usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove o banimento ou o timeout de um usuário.
     * @param int $id_usuario
     * @return bool
     */
    public function pardon(int $id_usuario): bool
    {
        try {
            $query = '
            MATCH (u:Usuario) WHERE id(u) = $id_usuario
            SET u.banned = false, u.timeoutUntil = null
        ';
            $this->client->run($query, ['id_usuario' => $id_usuario]);
            return true;
        } catch (\Exception $e) {
            error_log("Erro ao perdoar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Migra usuários renomeando a propriedade 'admin' para 'isAdmin'
     * e adiciona as propriedades banned e timeoutUntil
     * @return bool
     */
    public function migrateAdminProperty(): bool
    {
        try {
            // Rename 'admin' to 'isAdmin'
            $query1 = 'MATCH (u:Usuario) WHERE u.admin IS NOT NULL SET u.isAdmin = u.admin REMOVE u.admin';
            $this->client->run($query1);

            // Ensure all users have isAdmin (set to false if missing)
            $query2 = 'MATCH (u:Usuario) SET u.isAdmin = COALESCE(u.isAdmin, false)';
            $this->client->run($query2);

            // Ensure all users have banned
            $query3 = 'MATCH (u:Usuario) SET u.banned = COALESCE(u.banned, false)';
            $this->client->run($query3);

            // Ensure all users have timeoutUntil
            $query4 = 'MATCH (u:Usuario) SET u.timeoutUntil = COALESCE(u.timeoutUntil, null)';
            $this->client->run($query4);

            return true;
        } catch (\Exception $e) {
            error_log("Erro ao migrar propriedade admin: " . $e->getMessage());
            return false;
        }
    }
}