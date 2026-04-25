<?php

namespace Model;

class Cita extends ActiveRecord {
    // Base DE DATOS
    protected static $tabla = 'citas';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'usuarioId'];

    // Propiedades que se heredarán
    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;

    // Constructor
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioId = $args['usuarioId'] ?? '';
    }
}