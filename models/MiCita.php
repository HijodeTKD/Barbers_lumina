<?php

namespace Model;

class MiCita extends ActiveRecord{
    protected static $tabla = 'citasServicios';
    protected static $columnasDB = ['id', 'fecha', 'hora', 'servicio', 'precio'];

    public $id;
    public $fecha;
    public $hora;
    public $servicio;
    public $precio;

    public function __construct()
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->servicio = $args['servicio'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }
}