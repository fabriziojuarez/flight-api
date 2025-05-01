<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once "./functions/Partner.php";
require_once "./functions/Training.php";

// MANEJO DE ERRORES DE MANERA GLOBAL
Flight::map('error', function (Exception $ex) {
    $code = $ex->getCode() ?: 500;
    Flight::json([
        'error' => true,
        'type' => get_class($ex),
        'message' => $ex->getMessage()
    ], $code);
});

// CAPTURADORES DE TOKEN EN HEADERS
function getToken()
{
    $headers = apache_request_headers();
    if(empty($headers['Authorization'])){
        throw new Exception("Token de verificacion es requerido", 401);
    }
    $auth = $headers['Authorization'];
    $autharray = explode(" ", $auth);
    $token = $autharray[1];
    $decoded = JWT::decode($token, new Key($_ENV['key'], $_ENV['algcod']));
    return $decoded;
}
function validarToken()     // ACCESO PUBLICO
{
    $info = getToken();
    $query = Flight::db()->prepare("SELECT * FROM partners WHERE id_partner = :id");
    $query->execute([":id" => $info->data]);
    $result = $query->fetch();
    return $result;
}
function validarTokenBT()   // SOLO ACCESO PARA BT
{
    $info = getToken();
    $query = Flight::db()->prepare("SELECT * FROM partners WHERE role_partner='BT' AND id_partner=:id");
    $query->execute([":id"=>$info->data]);
    $result = $query->fetch();
    return $result;
}

// BUSCAR: RUTAS PROTEGIDAS
Flight::route('/index.php', function () {
    //echo "Api levantada :3";
});

Flight::route('POST /auth', [Partner::class, 'auth']);

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
