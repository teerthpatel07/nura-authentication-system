<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use MongoDB\Client;
use Predis\Client as RedisClient;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


/*
|--------------------------------------------------------------------------
| MySQL
|--------------------------------------------------------------------------
*/

$mysql = new PDO(
    "mysql:host={$_ENV['MYSQL_HOST']};port={$_ENV['MYSQL_PORT']};dbname={$_ENV['MYSQL_DATABASE']};charset=utf8mb4",
    $_ENV['MYSQL_USERNAME'],
    $_ENV['MYSQL_PASSWORD'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
);


/*
|--------------------------------------------------------------------------
| MongoDB
|--------------------------------------------------------------------------
*/

$mongo = new Client($_ENV['MONGODB_URI']);

$mongoDb = $mongo->selectDatabase(
    $_ENV['MONGODB_DATABASE']
);


/*
|--------------------------------------------------------------------------
| Redis
|--------------------------------------------------------------------------
*/

$redisOptions = [
    'scheme' => 'tcp',
    'host' => $_ENV['REDIS_HOST'],
    'port' => $_ENV['REDIS_PORT']
];

if (!empty($_ENV['REDIS_PASSWORD'])) {
    $redisOptions['password'] = $_ENV['REDIS_PASSWORD'];
}

$redis = new RedisClient($redisOptions);

$redis->connect();