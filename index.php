<?php

require_once "functions/Partner.php";
require_once "functions/Training.php";

// MANEJO DE ERRORES DE MANERA GLOBAL
Flight::map('error', function(Exception $ex){
    $code = $ex->getCode() ?: 500;
    Flight::json([
        'error' => true,
        'message' => $ex->getMessage()
    ], $code);
});

//MEJORAR ESTO
// BUSCAR: RUTAS PROTEGIDAS
Flight::route('/index.php', function(){
    //echo "Api levantada :3";
});

// PARTNERS
Flight::route('GET /partners', [Partner::class, 'index']);
Flight::route('GET /partners/@id', [Partner::class, 'show']);
Flight::route('POST /partners', [Partner::class, 'store']);
Flight::route('PUT /partners/@id', [Partner::class, 'update']);
Flight::route('DELETE /partners/@id', [Partner::class, 'delete']);

// TRAINING
Flight::route('GET /training', [Training::class, 'index']);
Flight::route('GET /training/@id', [Training::class, 'show']);
Flight::route('POST /training', [Training::class, 'store']);
Flight::route('PUT /training/@id', [Training::class, 'update']);
Flight::route('DELETE /training/@id', [Training::class, 'delete']);


Flight::start();