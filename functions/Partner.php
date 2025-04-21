<?php

require_once "./config/connection.php";

class Partner
{
    public static function index()
    {
        try {
            $query = Flight::db()->prepare("SELECT * FROM partners");
            $query->execute();
            $result = $query->fetchAll();
            $data = [];
            foreach ($result as $row) {
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

            $response = [
                'status' => 'success',
                'list_partners' => $data,
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
            $state = Flight::request()->data->state;
            $user = Flight::request()->data->user;
            $code = Flight::request()->data->code;
            $role = Flight::request()->data->role;
            $name = Flight::request()->data->name;
            $lastname = Flight::request()->data->lastname;
            $startdate = Flight::request()->data->startdate;
            $query = Flight::db()->prepare("INSERT INTO partners (
                state_partner,
                user_partner,
                code_partner,
                role_partner,
                name_partner,
                lastname_partner,
                startdate_partner
                ) VALUES (:state, :user, :code, :role, :name, :lastname, :startdate)");
            $query->execute([
                ":state" => $state,
                ":user" => $user,
                ":code" => $code,
                ":role" => $role,
                ":name" => $name,
                ":lastname" => $lastname,
                ":startdate" => $startdate,
            ]);

            $response = [
                'status' => 'success',
                'data' => [
                    'id' => Flight::db()->lastInsertId(),
                    'state' => $state,
                    'user' => $user,
                    'code' => $code,
                    'role' => $role,
                    'name' => $name,
                    'lastname' => $lastname,
                    'startdate' => $startdate,
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
}
