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
                $partners[] = [
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

            Flight::json([
                'success' => true,
                'message' => 'Partners listados',
                'list_partners' => $partners,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function show($id)
    {
        try{
            if(!is_numeric($id)){
                throw new Exception("Id '$id' no es un valor valido", 400);
            }

            $query = Flight::db()->prepare("SELECT * FROM partners WHERE id_partner = :id");
            $query->execute([':id' => $id]);
            $result = $query->fetch();

            if($query->rowCount() === 0){
                throw new EXception("Partner con id '$id' no encontrado", 404);
            }

            $partner = [
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

            Flight::json([
                'success' => true,
                'message' => 'Partner encontrado',
                'partner' => $partner,
            ]);
        }catch(Exception $e){
            Flight::error($e);
        }
    }

    public static function store()
    {
        try {
            $user = Flight::request()->data->user;
            $code = Flight::request()->data->code;
            $role = Flight::request()->data->role;
            $name = Flight::request()->data->name;
            $lastname = Flight::request()->data->lastname;
            $date = date("Y-m-d");

            if(empty($user)){
                throw new Exception("Usuario de partner es requerido", 400);
            }
            if(empty($code)){
                throw new Exception("Codigo para partner es requerido", 400);

            }
            if(empty($role)){
                throw new Exception("Rol de partner es requerido", 400);
            }
            if($role != "PT" && $role != "FT" && $role != "BT" && $role != "SSV" && $role != "SM"){
                throw new Exception("Solo se permiten los valores 'PT', 'FT', 'BT', 'SSV' y 'SM' en el campo role", 400);
            }
            if(empty($name)){
                throw new Exception("Nombre de partner es requerido", 400);
            }
            if(empty($lastname)){
                throw new Exception("Apellido de partner es requerido", 400);
            }

            $query = Flight::db()->prepare("INSERT INTO partners (
                user_partner,
                code_partner,
                role_partner,
                name_partner,
                lastname_partner,
                startdate_partner
                ) VALUES (:user, :code, :role, :name, :lastname, :startdate)");
            $query->execute([
                ":user" => $user,
                ":code" => $code,
                ":role" => $role,
                ":name" => $name,
                ":lastname" => $lastname,
                ":startdate" => $date,
            ]);

            if($query->rowCount() === 0){
                throw new Exception("Partner no insertado", 400);
            }

            $partner = [
                'id' => Flight::db()->lastInsertId(),
                    'estado' => 'ACTIVO',
                    'usuario' => $user,
                    'codigo' => $code,
                    'posicion' => $role,
                    'nombres' => $name,
                    'apellidos' => $lastname,
                    'fecha_inicio' => $date,
            ];

            Flight::json([
                'success' => true,
                'message' => 'Partner creado correctamente',
                'partner' => $partner,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }

    public static function update($id)
    {
        try{
            if(!is_numeric($id)){
                throw new Exception("Id '$id' no es un valor valido", 400);
            }

            $state = Flight::request()->query->state;
            $user = Flight::request()->query->user;
            $code =Flight::request()->query->code;
            $role =Flight::request()->query->role;
            $name =Flight::request()->query->name;
            $lastname =Flight::request()->query->lastname;
            $date = date("Y-m-d");
            
            if(empty($state)){
                throw new Exception("Nuevo estado de partner es requerido", 400);
            }
            if($state != "INNACTIVO" && $state !="BAJA MEDICA" && $state !="VACACIONES" && $state != "ACTIVO"){
                throw new Exception("Solo se permiten los valores 'INNACTIVO', 'BAJA MEDICA', 'VACACIONES', 'ACTIVO' en el campo estado", 400);
            }
            if(empty($user)){
                throw new Exception("Nuevo usuario de partner es requerido", 400);
            }
            if(empty($code)){
                throw new Exception("Nuevo codigo de partner es requerido", 400);
            }
            if(empty($role)){
                throw new Exception("Nuevo rol de partner es requerido", 400);
            }
            if($role != "PT" && $role != "FT" && $role != "BT" && $role != "SSV" && $role != "SM"){
                throw new Exception("Solo se permiten los valores 'PT', 'FT', 'BT', 'SSV' y 'SM' en el campo role", 400);
            }
            if(empty($name)){
                throw new Exception("Nuevo nombre de partner es requerido", 400);
            }
            if(empty($lastname)){
                throw new Exception("Nuevo apellido de partner es requerido", 400);
            }

            $query = Flight::db()->prepare("UPDATE partners SET state_partner=:state, user_partner=:user, code_partner=:code, role_partner=:role, name_partner=:name, lastname_partner=:lastname, update_partner=:date WHERE id_partner=:id");
            $query->execute([
                ":state" => $state,
                ":user" => $user,
                ":code" => $code,
                ":role" => $role,
                ":name" => $name,
                ":lastname" =>$lastname,
                ":date" => $date,
                ":id" => $id,
            ]);

            if($query->rowCount() === 0){
                throw new Exception("Partner no actualizado", 400);
            }

            $partner = [
                'id' => $id,
                'estado' => $state,
                'usuario' => $user,
                'codigo' => $code,
                'posicion' => $role,
                'nombres' => $name,
                'apellidos' => $lastname,
                'fecha_actualizado' => $date,
            ];

            Flight::json([
                'success' => true,
                'message' => 'Partner actualizado correctamente',
                'partner' => $partner,
            ]);
        }catch(Exception $e){
            Flight::error($e);
        }
    }

    public static function delete($id){
        try{
            if(!is_numeric($id)){
                throw new Exception("Id '$id' no es un valor valido", 400);
            }

            $query = Flight::db()->prepare("DELETE FROM partners WHERE id_partner = :id");
            $query->execute([':id' => $id]);

            if($query->rowCount() === 0){
                throw new Exception("Partner con id '$id' no se puede eliminar", 400);
            }

            Flight::json([
                'success' => true,
                'message' => 'Partner eliminado correctamente',
            ]);
        }catch(Exception $e){
            Flight::error($e);
        }
    }
}
