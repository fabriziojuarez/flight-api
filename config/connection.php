<?php

require "./vendor/autoload.php";

$params = [
    'pgsql:host=192.168.1.54;dbname=db_cafeteria',
    'postgres',
    '759878'
];

Flight::register('db', 'PDO', $params);