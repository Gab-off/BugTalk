<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Authentication\Authenticate;

$client = ClientBuilder::create()
    ->withDriver('default', $_ENV['NEO4J_URI'], Authenticate::basic($_ENV['NEO4J_USER'], $_ENV['NEO4J_PASS']))
    ->build();

try {
    echo "🧹 Iniciando limpeza do banco de dados...\n\n";

    // 1. Deletar todos os comentários
    echo "❌ Deletando comentários...";
    $client->run('MATCH (c:Comentario) DETACH DELETE c');
    echo " ✅ Feito!\n";

    // 2. Deletar todos os posts
    echo "❌ Deletando posts...";
    $client->run('MATCH (p:Post) DETACH DELETE p');
    echo " ✅ Feito!\n";

    // 3. Deletar todas as tags
    echo "❌ Deletando tags...";
    $client->run('MATCH (t:Tag) DETACH DELETE t');
    echo " ✅ Feito!\n";

    echo "\n✨ Banco de dados limpo com sucesso!\n";
    echo "📝 Usuários mantidos para reutilização.\n";

} catch (\Exception $e) {
    echo "❌ Erro ao limpar banco: " . $e->getMessage() . "\n";
}
?>
