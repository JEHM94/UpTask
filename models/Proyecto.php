<?php

namespace Model;

class Proyecto extends ActiveRecord
{
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'usuarios_id'];

    public $id;
    public $proyecto;
    public $url;
    public $usuarios_id;

    public function __construct($arr = [])
    {
        $this->id = $arr['id'] ?? null;
        $this->proyecto = $arr['proyecto'] ?? '';
        $this->url = $arr['url'] ?? '';
        $this->usuarios_id = $arr['usuarios_id'] ?? null;
    }

    public function validar($tipoValidacion = null)
    {
        if (!$this->proyecto) {
            self::$alertas[ERROR][] = 'El nombre del proyecto es obligatorio';
        }

        return self::$alertas;
    }
}
