<?php

class Topic
{
    public static function indexAll()
    {
        try {
            if (!validarTokenBT()) {
                throw new Exception("Rol 'BT' es requerido");
            }
            $query = Flight::db()->prepare("SELECT * FROM topics");
            $query->execute();
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
    public static function indexTraining($idTraining)
    {
        try {
            if(empty($idTraining) || !is_numeric($idTraining)){
                throw new Exception("Id de capacitacion es requerido", 400);
            }

            $query = Flight::db()->prepare("SELECT * FROM topics WHERE training_topic = :training");
            $query->execute([":training" => $idTraining]);

            if($query->rowCount() === 0){
                throw new Exception("eee");
            }
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
    public static function store()
    {
        try {
            if (!validarToken()) {
                throw new Exception("Rol 'BT' es requerido", 401);
            }
            $name = Flight::request()->data->name;
            $score = Flight::request()->data->score;
            $training = Flight::request()->data->training;

            if(empty($name)){
                throw new Exception("Nombre de tema es requerido");
            }
            if(empty($score) || !is_numeric($score)){
                throw new Exception("Puntaje numerico para tema es requerido", 400);
            }
            if(empty($training) || !is_numeric($training)){
                throw new Exception("Id de capacitacion es requerido", 400);
            }

            $query = Flight::db()->prepare("INSERT INTO topics(name_topic, score_topic, training_topic) VALUES (:name, :score, :training)");
            $query->execute([
                ":name" => $name,
                ":score" => $score,
                ":training" => $training
            ]);
            if($query->rowCount() === 0){
                throw new Exception("Tema no insertado", 400);
            }

            $topic = ["id" => Flight::db()->lastInsertId(),
            "nombre" => $name,
            "puntaje" => $score,
            "capacitacion" => $training];

            Flight::json([
                "success" => true,
                "message" => "Tema creado correctamente",
                "tema" => $topic,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
}
