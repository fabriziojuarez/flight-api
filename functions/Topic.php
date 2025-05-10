<?php

require_once "./config/connection.php";

class Topic
{
    public static function index()
    {
        try {
            if (!validarTokenBT()) {
                throw new Exception("Rol 'BT' es requerido");
            }
            $query = Flight::db()->prepare("SELECT * FROM topics");
            $query->execute();
            $results = $query->fetchAll();
            foreach ($results as $row) {
                $topics[] = [
                    'id' => $row['id_topic'],
                    'nombre' => $row['name_topic'],
                    'puntaje' => $row['score_topic'],
                    'id capacitacion' => $row['training_topic'],
                ];
            }

            Flight::json([
                'success' => true,
                'message' => 'Temas listados',
                'temas' => $topics,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
    public static function indexTopics($idTraining)
    {
        try {
            if (!validarToken()) {
                throw new Exception("Autentificarte es requerido", 401);
            }
            if (empty($idTraining) || !is_numeric($idTraining)) {
                throw new Exception("Id numerico de capacitacion es requerido", 400);
            }

            $query = Flight::db()->prepare("SELECT * FROM topics WHERE training_topic = :training");
            $query->execute([":training" => $idTraining]);
            $results = $query->fetchAll();

            if ($query->rowCount() === 0) {
                throw new Exception("Capacitacion con id '$idTraining' no contiene temas");
            }

            foreach ($results as $row) {
                $topics[] = [
                    'id' => $row['id_topic'],
                    'nombre' => $row['name_topic'],
                    'puntaje' => $row['score_topic'],
                ];
            }

            Flight::json([
                'success' => true,
                'message' => "Temas listados de Capacitacion con id '$idTraining'",
                'temas' => $topics,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
    public static function show($id)
    {
        try {
            if (!validarToken()) {
                throw new Exception("Autentificarte es requerido", 401);
            }
            if (empty($id) || !is_numeric($id)) {
                throw new Exception("Id numerico de tema es requerido", 400);
            }

            $query = Flight::db()->prepare("SELECT * FROM topics WHERE id_topic = :id");
            $query->execute([":id" => $id]);
            $result = $query->fetch();

            if ($query->rowCount() === 0) {
                throw new EXception("Tema con id '$id' no encontrado", 404);
            }

            $topic = [
                'id' => $result['id_topic'],
                'nombre' => $result['name_topic'],
                'puntaje' => $result['score_topic'],
                'id capacitacion' => $result['training_topic'],
            ];

            Flight::json([
                'success' => true,
                'message' => 'Tema encontrado',
                'tema' => $topic,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function store()
    {
        try {
            // if (!validarTokenBT()) {
            //     throw new Exception("Rol 'BT' es requerido", 401);
            // }
            $name = Flight::request()->data->name;
            $score = Flight::request()->data->score;
            $training = Flight::request()->data->training;

            if (empty($name)) {
                throw new Exception("Nombre de tema es requerido");
            }
            if (empty($score) || !is_numeric($score)) {
                throw new Exception("Puntaje numerico para tema es requerido", 400);
            }
            if (empty($training) || !is_numeric($training)) {
                throw new Exception("Id numerico de capacitacion es requerido", 400);
            }

            // REVISA SI EXISTE LA CAPACITACION CON DICHA ID
            $q = Flight::db()->prepare("SELECT * FROM training WHERE id_training=$training");
            $q->execute();
            if ($q->rowCount() === 0) {
                throw new Exception("Id de capacitacion no encontrado", 400);
            }

            $query = Flight::db()->prepare("INSERT INTO topics(name_topic, score_topic, training_topic) VALUES(:name, :score, :training)");
            $query->execute([
                ":name" => $name,
                ":score" => $score,
                ":training" => $training
            ]);

            if ($query->rowCount() === 0) {
                throw new Exception("Tema no insertado", 400);
            }

            $topic = [
                "id" => Flight::db()->lastInsertId(),
                "nombre" => $name,
                "puntaje" => $score,
                "id capacitacion" => $training
            ];

            Flight::json([
                "success" => true,
                "message" => "Tema creado correctamente",
                "tema" => $topic,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function update($id)
    {
        try {
            $name = Flight::request()->data->name;
            $score = Flight::request()->data->score;
            $training = Flight::request()->data->training;

            if(!is_numeric($id)){
                throw new Exception("Id '$id' no es un valor valido", 400);
            }   
            if (empty($name)) {
                throw new Exception("Nuevo nombre para tema es requerido", 400);
            }
            if (empty($score) || !is_numeric($score)) {
                throw new Exception("Nuevo puntaje para tema es requerido", 400);
            }
            if (empty($training) || !is_numeric($training)) {
                throw new Exception("Nuevo id de capacitacion es requerido", 400);
            }

            $query = Flight::db()->prepare("UPDATE topics SET name_topic = :name, score_topic = :score, training_topic = :training WHERE id_topic=:id");
            $query->execute([
                ":name" => $name,
                ":score" => $score,
                ":training" => $training,
                ":id" => $id,
            ]);

            if ($query->rowCount() === 0) {
                throw new Exception("Tema con id '$id' no se actualizo", 400);
            }

            $topic = [
                'id' => $id,
                'name' => $name,
                'score' => $score,
                'training' => $training,
            ];

            Flight::json([
                'success' => true,
                'message' => 'Topic actualizado correctamente',
                'topic' => $topic,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function delete($id)
    {
        try {
            if (!is_numeric($id)) {
                throw new Exception("Id '$id' no es un valor valido");
            }

            $query = Flight::db()->prepare("DELETE FROM topics WHERE id_topic = :id");
            $query->execute([":id" => $id]);

            if ($query->rowCount() === 0) {
                throw new Exception("Tema con id '$id' no se puede eliminar", 400);
            }

            Flight::json([
                'success' => true,
                'message' => 'Tema eliminado correctamente',
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
}
