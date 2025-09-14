<?php
require 'vendor/autoload.php';

use Laudis\Neo4j\ClientBuilder;

$uri = 'neo4j://localhost:7687';
$user = 'neo4j';
$password = 'password';

$client = null;
try {
    $client = ClientBuilder::create()->withDriver('default', $uri, \Laudis\Neo4j\Authentication\Authenticate::basic($user, $password))->build();
} catch (Exception $e) {
    die("Erro ao conectar com o Neo4j: " . $e->getMessage());
}