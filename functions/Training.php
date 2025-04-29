<?php

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
                    'estado' => $row['state_training'],
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

    public static function show($id)
    {
        try {
            $query = Flight::db()->prepare("SELECT * FROM training WHERE id_training = :id");
            $query->execute([':id' => $id]);
            $result = $query->fetch();

            $data = [
                'id' => $result['id_training'],
                'nombre' => $result['name_training'],
                'estado' => $result['state_training']
            ];

            $response = [
                'status' => 'success',
                'training' => $data,
            ];
        } catch (Exception $e) {
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
                    'id' => Flight::db()->lastInsertId(),
                    'nombre' => $name,
                    'estado' => 'ACTIVO',
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

    public static function update($id)
    {
        try {
            $name = Flight::request()->query->name;
            $state = Flight::request()->query->state;

            $query = Flight::db()->prepare("UPDATE training SET name_training=:name, state_training=:state WHERE id_training=:id");
            $query->execute([
                ":name" => $name,
                ':state' => $state,
                ":id" => $id
            ]);

            if($query->rowCount() == 0){
                $response = [
                    'status' => 'error',
                    'error' => 'Actualizacion no realizada',
                ];
                Flight::json($response);
                return;
            }

            $training = [
                'id' => $id,
                'nombre' => $name,
                'estado' => $state,
            ];

            $response = [
                'status' => 'success',
                'msg' => 'Capacitacion actualizada'
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
        Flight::json($response);
    }

    public static function delete($id)
    {
        try {
            $query = Flight::db()->prepare("DELETE FROM training WHERE id_training = :id");
            $query->execute([':id' => $id]);

            if ($query->rowCount() == 0) {
                $response = [
                    'status' => 'error',
                    'error' => 'Eliminacion no realizada',
                ];
                Flight::json($response);
                return;
            }

            $response = [
                'status' => 'success',
                'msg' => 'Capacitacion eliminada',
            ];
        } catch (Exception $e) {
            $response = [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
        Flight::json($response);
    }
}
