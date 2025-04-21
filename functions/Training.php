<?php

use flight\net\Request;

require_once "./config/connection.php";

class Training
{
    public static function index()
    {
        try {
            $query = Flight::db()->prepare("SELECT * FROM training");
            $query->execute();
            $results = $query->fetchAll();
            foreach ($results as $row) {
                $data[] = [
                    'id' => $row['id_training'],
                    'nombre' => $row['name_training'],
                ];
            }
            $response = [
                'status' => 'success',
                'list_training' => $data,
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
        Flight::json($response);
    }

    public static function show($id){
        try{
            $query = Flight::db()->prepare("SELECT * FROM training WHERE id_training = :id");
            $query->execute([':id' => $id]);
            $result = $query->fetch();

            $data = [
                'id' => $result['id_training'],
                'nombre' => $result['name_training'],
            ];

            $response = [
                'status' => 'success',
                'training' => $data,
            ];
        }catch(Exception $e){
            $response = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
        Flight::json($response);
    }

    public static function store()
    {
        try {
            $name = Flight::request()->data->name;

            $query = Flight::db()->prepare("INSERT INTO training (name_training) VALUES (:name)");
            $query->execute([":name" => $name]);

            $response = [
                'status' => 'success',
                'training' => [
                    'name' => $name,
                ],
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
        Flight::json($response);
    }

    public static function delete(){
        try{

            $query = Flight::db()->prepare("DELETE FROM training WHERE id_training = :id");

        }catch(Exception $e){

        }
    }
}