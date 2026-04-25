<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;


class APIController {
    public static function index(){
        $servicios = Servicio::all();
        echo json_encode($servicios);
        // debuguear($servicios);
    }
    public static function guardar(){

    //almacen la cita y devuelve un resultado
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();
        $id = $resultado['id'];

        $idServicios = explode( ",",  $_POST['servicios']); //obtener los ids de los servicios, separados por comas
        
        //almana los servicios de la cita, recorriendo el array de los ids de los servicios y creando un nuevo objeto CitaServicio por cada id de servicio, y guardando el objeto en la base de datos
        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header ('Location: ' . $_SERVER['HTTP_REFERER']);
            // echo json_encode(['resultado' => $cita]);
        }
    }



}