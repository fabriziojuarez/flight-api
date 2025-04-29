<?php

require "./vendor/autoload.php";

date_default_timezone_set("America/Lima");

$params = [
    'pgsql:host=192.168.1.54;
     dbname=db_cafeteria',
    'postgres',
    '759878'
];

Flight::register('db', 'PDO', $params, function($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});
