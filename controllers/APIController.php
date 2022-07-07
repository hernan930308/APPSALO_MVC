<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

Class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios, JSON_UNESCAPED_UNICODE);
    }

    public static function guardar() {

        // Almacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar(); 

        $id = $resultado['id'];

        // Almacena las Cita y el servicio

        // Almacena los servicios con el id de la cita
        $idServicios = explode(",", $_POST['servicios']);
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }


        echo json_encode(['resultado' => $resultado], JSON_UNESCAPED_UNICODE);
        
    }

    public static function eliminar () {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            sleep(2);
            header('Location: ' .  $_SERVER["HTTP_REFERER"]);
        }
      
    }
}
