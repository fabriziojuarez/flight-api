<?php

require_once "./config/connection.php";

class Partner{
    public static function index(){
        $query = Flight::db()->prepare("SELECT * FROM partners");
        $query->execute();
        $result = $query->fetchAll();
        $data = [];   
        foreach($result as $row){
            $data[] = [
                'id' => $row['id_partner'],
                'estado' => $row['state_partner'],
                'usuario' => $row['user_partner'],
                'codigo' => $row['code_partner'],
                'posicion' => $row['role_partner'],
                'nombres' => $row['name_partner'],
                'apellidos' => $row['lastname_partner'],
                'fecha_inicio' => $row['startdate_partner'],
                'fecha_actualizado' => $row['update_partner'],
            ];
        }
        Flight::json($data);
    }
}