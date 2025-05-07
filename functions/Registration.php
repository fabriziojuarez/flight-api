<?php

class Registration
{
    public static function index()
    {
        try {
            $query = Flight::db()->prepare("SELECT * FROM registrations");
            $query->execute();
            $results = $query->fetchAll();

            foreach ($results as $row) {
                $registrations[] = [
                    'id' => $row['id_registration'],
                    'capacitacion' => $row['training_registration'],
                    'partner' => $row['partner_registration'],
                    'fecha' => $row['date_registration'],
                ];
            }

            Flight::json([
                'success' => true,
                'message' => 'Registros listados',
                'registros' => $registrations,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
    public static function show($id)
    {
        try {
            if (empty($id) || !is_numeric($id)) {
                throw new Exception("Id numerico de registro es requerido", 400);
            }

            $query = Flight::db()->prepare("SELECT * FROM registrations WHERE id_registration = :id");
            $query->execute([":id" => $id]);
            $result = $query->fetch();

            if ($query->rowCount() === 0) {
                throw new Exception("Registro con id '$id' no encontrado", 404);
            }

            $registration = [
                'id' => $result['id_registration'],
                'capacitacion' => $result['training_registration'],
                'partner' => $result['partner_registration'],
                'fecha' => $result['date_registration'],
            ];

            Flight::json([
                'success' => true,
                'message' => 'Registro encontrado',
                'registro' => $registration,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
    public static function store()
    {
        try {
            $training = Flight::request()->data->training;
            $partner = Flight::request()->data->partner;
            $date = date("Y-m-d");

            if (empty($training) || !is_numeric($training)) {
                throw new Exception("Id numerico de capacitacion es requerido", 400);
            }
            if (empty($partner) || !is_numeric($partner)) {
                throw new Exception("Id numerico de partner es requerido", 400);
            }

            $query = Flight::db()->prepare("INSERT INTO registration(training_registration, partner_registration, date_registration) VALUES(:training, :partner, :date)");
            $query->execute([
                ":training" => $training,
                ":partner" => $partner,
                ":date" => $date,
            ]);

            if ($query->rowCount() === 0) {
                throw new Exception("Registro no insertado", 400);
            }

            $registration = [
                'id' => Flight::db()->lastInsertId(),
                'capacitacion' => $training,
                'partner' => $partner,
                'fecha' => $date,
            ];

            Flight::json([
                'success' => true,
                'message' => 'Registro creado correctamente',
                'registro' => $registration,
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
    public static function delete($id)
    {
        try {
            if(empty($id) || !is_numeric($id)){
                throw new Exception("Id '$id' es un valor invalido", 400);
            }

            $query = Flight::db()->prepare("DELETE FROM registrations WHERE id_registration=:id");
            $query->execute([":id" => $id]);

            if($query->rowCount()===0){
                throw new Exception("Registro con id '$id' no se puede eliminar", 400);
            }

            Flight::json([
                'success' => true,
                'message' => 'Registro eliminado correctamente',
            ]);
        } catch (Exception $e) {
            Flight::error($e);
        }
    }
}
