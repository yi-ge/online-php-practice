<?php
$DBCONFIG = [
    'database_type' => 'mysql',
    'database_name' => $_ENV['MYSQL_DBNAME'],
    'server' => $_ENV['MYSQL_HOST'],
    'username' => $_ENV['MYSQL_USERNAME'],
    'password' => $_ENV['MYSQL_PASSWORD'],
    'charset' => 'utf8mb4',
    'port' => $_ENV['MYSQL_PORT'],
];

$REDISCONFIG = [
    'host' => $_ENV['REDIS_HOST'],
    'port' => $_ENV['REDIS_PORT'],
    'password' => $_ENV['REDIS_PASSWORD'],
    'database' => 0
];
