<?php

require "./vendor/autoload.php";

$params = [
    'pgsql:host=localhost;dbname=db_cafeteria',
    'postgres',
    '759878'
];

Flight::register('db', 'PDO', $params);