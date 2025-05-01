<?php

require_once "./config/connection.php";

class Training
{
    public static function index()
    {
        try {
            if(!validarTokenBT()){
                throw new Exception("Rol 'BT' es requerido", 401);
            }

            $query = Flight::db()->prepare("SELECT * FROM training");
            $query->execute();
            $results = $query->fetchAll();
            foreach ($results as $row) {
                $trainings[] = [
                    'id' => $row['id_training'],
                    'nombre' => $row['name_training'],
                    'estado' => $row['state_training'],
                ];
            }
            Flight::json([
                'success' => true,
                'message' => 'Capacitaciones listadas',
                'list_capacitaciones' => $trainings,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function show($id)
    {
        try {
            if(!is_numeric($id)){
                throw new Exception("Id '$id' no es un valor valido", 401);
            }

            $query = Flight::db()->prepare("SELECT * FROM training WHERE id_training = :id");
            $query->execute([':id' => $id]);
            $result = $query->fetch();

            if($query->rowCount() === 0){
                throw new Exception("Capacitacion con Id '$id' no encontrado", 404);
            }

            $training = [
                'id' => $result['id_training'],
                'nombre' => $result['name_training'],
                'estado' => $result['state_training']
            ];

            Flight::json([
                'success' => true,
                'message' => 'Capacitacion encontrada',
                'training' => $training,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function store()
    {
        try {
            if(!validarTokenBT()){
                throw new Exception("Rol 'BT' es requerido", 401);
            }

            $name = Flight::request()->data->name;

            if (empty($name)) {
                throw new Exception("Nombre de capacitacion es requerida", 400);
            }

            $query = Flight::db()->prepare("INSERT INTO training(name_training) VALUES(:name)");
            $query->execute([":name" => $name]);

            if ($query->rowCount() === 0) {
                throw new Exception("Capacitacion no insertada", 500);
            }

            $training = [
                'id' => Flight::db()->lastInsertId(),
                'nombre' => $name,
                'estado' => 'ACTIVO',
            ];

            Flight::json([
                'success' => true,
                'message' => 'Capacitacion creada correctamente',
                'training' => $training,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function update($id)
    {
        try {
            if(!validarTokenBT()){
                throw new Exception("Rol 'BT' es requerido", 401);
            }

            if(!is_numeric($id)){
                throw new Exception("Id '$id' no es un valor valido", 400);
            }

            $name = Flight::request()->query->name;
            $state = Flight::request()->query->state;

            if (empty($name)) {
                throw new Exception("Nuevo nombre de capacitacion es requerido", 400);
            }
            if (empty($state)) {
                throw new Exception("Nuevo estado de capacitacion es requerido", 400);
            }
            if($state != "ACTIVO" && $state != "INNACTIVO"){
                throw new Exception("Solo se permiten los valores 'ACTIVO' e 'INNACTIVO' en el campo state");
            }

            $query = Flight::db()->prepare("UPDATE training SET name_training=:name, state_training=:state WHERE id_training=:id");
            $query->execute([
                ":name" => $name,
                ':state' => $state,
                ":id" => $id
            ]);

            if ($query->rowCount() === 0) {
                throw new Exception("Capacitacion no actualizada", 400);
            }

            $training = [
                'id' => $id,
                'nombre' => $name,
                'estado' => $state,
            ];

            Flight::json([
                'success' => true,
                'message' => 'Capacitacion actualizada correctamente',
                'training' => $training,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function delete($id)
    {
        try {
            if(!validarTokenBT()){
                throw new Exception("Rol 'BT' es requerido", 401);
            }

            if(!is_numeric($id)){
                throw new Exception("Id '$id' no es un valor valido", 400);
            }

            $query = Flight::db()->prepare("DELETE FROM training WHERE id_training = :id");
            $query->execute([':id' => $id]);

            if ($query->rowCount() === 0) {
                throw new Exception("Capacitacion con id '$id' no se puede eliminar", 400);
            }

            Flight::json([
                'success' => true,
                'message' => 'Capacitacion eliminada correctamente',
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
}
