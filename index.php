<?php

require 'vendor/autoload.php';

Flight::route('/', function () {
    Flight::json(['hola' => 'a todos']);
});

Flight::start();