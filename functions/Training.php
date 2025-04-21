<?php

use flight\net\Request;

require_once "./config/connection.php";

class Training{
    public static function index(){
        $query = Flight::db()->prepare("SELECT * FROM training");
        $query->execute();
        $results = $query->fetchAll();
        $data = [];
        foreach($results as $row){
            $data = [
                'id' => $row['id_training'],
                'nombre' => $row['name_training'],
            ];
        }
        Flight::json($data);
    }

    public static function store(){
        try{
            $name = Flight::request()->data->name;
            
            $query = Flight::db()->prepare("INSERT INTO training (name_training) VALUES (:name)");
            $query->execute([":name" => $name]);

            $response = [
                'status' => 'success',
                'training' => [
                    'id' => Flight::db()->lastInsertId(),
                    'name' => $name,
                ],
            ];
        }catch(Exception $e){
            $response = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
        Flight::json($response);
    }
}