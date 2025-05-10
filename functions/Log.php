<?php

use Firebase\JWT\ExpiredException;

class Log{
    public static function index(){
        try{
            $query = Flight::db()->prepare("SELECT * FROM logs");
            $query->execute();

            $results = $query->fetchAll();
            foreach($results as $row){
                $logs[] = [
                    'id' => $row["id_log"],
                    'partner' => $row['partner_log'],
                    'tema' => $row['topic_log'],
                    'nota' => $row['grade_log'],
                    'indate' => $row['indate_log'],
                    'update' => $row['update_log'],
                ];
            }

            Flight::json([
                'success' => true,
                'message' => 'Logs listados',
                'logs' => $logs,
            ]);
        }catch(Exception $e){
            Flight::error($e);
        }
    }
    public static function show($id){
        try{
            if(!is_numeric($id)){
                throw new Exception("Id '$id' no es un valor valido");
            }

            $query = Flight::db()->prepare("SELECT * FROM logs WHERE id_log=_id");
            $query->execute([":id"=>$id]);
            $result = $query->fetch();
            
            $log = [
                'id' => $result['id_log'],
                'partner' => $result['partner_log'],
                'tema' => $result['topic_log'],
                'nota' => $result['grade_log'],
                'fecha_inicio' => $result['indate_log'],
                'fecha_actualizado' => $result['update_log'],
            ];

            Flight::json([
                'success' => true,
                'message' => "Log con id '$id' encontrado",
                'log' => $log,
            ]);
        }catch(Exception $e){
            Flight::error($e);
        }
    }
    public static function store(){
        try{
            $partner = Flight::request()->data->partner;
            $topic = Flight::request()->data->topic;
            $grade = Flight::request()->data->grade;
            $indate = date("Y-m-d");

            if(empty($partner) || !is_numeric($partner)){
                throw new Exception("Id numerico de partner es requerido", 400);
            }
            if(empty($topic) || !is_numeric($topic)){
                throw new Exception("Id numerico de tema es requerido", 400);
            }
            if(empty($grade) || !is_numeric($grade)){
                throw new Exception("Nota numerica es requerido", 400);
            }

            $query = Flight::db()->prepare("INSERT INTO logs(partner_log, topic_log, grade_log, indate_log) VALUES(:partner, :topic, :grade, :indate)");
            $query->execute([
                ":partner" => $partner,
                ":topic" => $topic,
                ":grade" => $grade,
                ":indate" => $indate,
            ]);

            if($query->rowCount()===0){
                throw new Exception("Log se inserto correctamente", 400);
            }

            $log = [
                'id' => Flight::db()->lastInsertId(),
                'id_partner' => $partner,
                'id_tema' => $topic,
                'nota' => $grade,
                'fecha_creacion' => $indate,
            ];

            Flight::json([
                'success' => true,
                'message' => 'Log insertado correctamente',
                'log' => $log,
            ]);
        }catch(Exception $e){
            Flight::error($e);
        }
    }
    public static function update($id){
        try{
            $partner = Flight::request()->data->partner;
            $topic = Flight::request()->data->topic;
            $grade = Flight::request()->data->grade;
            $update = date("Y-m-d");

            if(!is_numeric($id)){
                throw new Exception("Id numerico de Log es requerido");
            }
            if(empty($partner) || !is_numeric($partner)){
                throw new Exception("Id numerico de partner es requerido", 400);
            }
            if(empty($topic) || !is_numeric($topic)){
                throw new Exception("Id numerico de tema es requerido", 400);
            }
            if(empty($grade) || !is_numeric($grade)){
                throw new Exception("Nota numerica es requerida", 400);
            }

            $query = Flight::db()->prepare("UPDATE logs SET partner_log=:partner, topic_log=:topic, grade_log=:grade, update_log=:update");
            $query->execute([
                ":partner" => $partner,
                ":topic" => $topic,
                ":grade" => $grade,
                ":update" => $update,
            ]);

            if($query->rowCount()===0){
                throw new Exception("Log con id '$id' no actualizado",400);
            }

            $log = [
                'id' => $id,
                'id_partner' => $partner,
                'id_tema' => $topic,
                'nota' => $grade,
                'fecha_actualizado' => $update,
            ];

            Flight::json([
                'success' => true,
                'message' => "Log con id '$id' actualizado correctamente",
                'log' => $log,
            ]);
        }catch(Exception $e){
            Flight::error($e);
        }
    }
    public static function delete($id){
        try{
            if(!is_numeric($id)){
                throw new Exception("Id '$id' es un valor invalido", 400);
            }

            $query = Flight::db()->prepare("DELETE FROM logs WHERE id_log=:id");
            $query->execute([":id" => $id]);

            if($query->rowCount()===0){
                throw new Exception("Log con id '$id' no se puede eliminar");
            }

            Flight::json([
                'success' => true,
                'message' => "Log con id '$id' eliminado correctamente",
            ]);
        }catch(Exception $e){
            Flight::error($e);
        }
    }
}