<?php

namespace Model;

class Tarea extends ActiveRecord
{
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id', 'nombre', 'estado', 'proyectos_id'];

    public $id;
    public $nombre;
    public $estado;
    public $proyectos_id;

    public function __construct($arr = [])
    {
        $this->id = $arr['id'] ?? null;
        $this->nombre = $arr['nombre'] ?? '';
        $this->estado = $arr['estado'] ?? 0;
        $this->proyectos_id = $arr['proyectos_id'] ?? null;
    }

}
