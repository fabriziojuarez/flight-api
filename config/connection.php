<?php

require "./vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

date_default_timezone_set("America/Lima");

$host = $_ENV['host'];
$db = $_ENV['db'];
$user = $_ENV['user'];
$pass = $_ENV['pass'];

$params = [
    "pgsql:host=$host;
    dbname=$db",
    "$user",
    "$pass"
];

Flight::register('db', 'PDO', $params);
