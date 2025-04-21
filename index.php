<?php

require_once "functions/Partner.php";
require_once "functions/Training.php";

//MEJORAR ESTO
// BUSCAR: RUTAS PROTEGIDAS
Flight::route('/', function(){
    echo "xd";
});

Flight::route('GET /partners', [Partner::class, 'index']);

Flight::route('POST /partners', function(){
    Partner::index();
});

Flight::route('GET /training', [Training::class, 'index']);
Flight::route('GET /training/@id', [Training::class, 'show']);
Flight::route('POST /training', [Training::class, 'store']);
Flight::route('DELETE /training/@id', [Training::class, 'delete']);


Flight::start();
