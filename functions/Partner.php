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

    public static function show($id){
        try{
            $query = Flight::db()->prepare("SELECT * FROM partners WHERE id_partner = :id");
            $query->execute([':id' => $id]);
            $result = $query->fetch();

            $data = [
                'id' => $result['id_partner'],
                'estado' => $result['id_partner'],
                'usuario' => $result['user_partner'],
                'codigo' => $result['code_partner'],
                'posicion' => $result['role_partner'],
                'nombres' => $result['name_partner'],
                'apellidos' => $result['lastname_partner'],
                'fecha_inicio' => $result['startdate_partner'],
                'fecha_actualizado' => $result['update_partner'],
            ];

            $response = [
                'status' => 'success',
                'partner' => $data,
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
                'partner' => [
                    'id' => Flight::db()->lastInsertId(),
                    'estado' => $state,
                    'usuario' => $user,
                    'codigo' => $code,
                    'posicion' => $role,
                    'nombres' => $name,
                    'apellidos' => $lastname,
                    'fecha_inicio' => $startdate,
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

    public static function delete($id){
        try{
            $query = Flight::db()->prepare("DELETE FROM partners WHERE id_partner = :id");
            $query->execute([':id' => $id]);

            if($query->rowCount() == 0){
                $response = [
                    'status' => 'error',
                    'error' => 'Eliminacion no realizada',
                ];
                Flight::json($response);
                return;
            }

            $response = [
                'status' => 'success',
                'msg' => 'Partner eliminado',
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
