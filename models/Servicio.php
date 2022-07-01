<?php

namespace Model;

class Servicio extends Activerecord {
    // Base de datos
    protected static $tabla =  'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    public function validar() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre del servicio es obligatorio';
        }
        if(!$this->precio) {
            self::$alertas['error'][] = 'El precio es obligatorio';
        }
        if(!is_numeric($this->precio) or $this->precio < 0) {
            self::$alertas['error'][] = 'El precio no es válido';
        }
        return self::$alertas;
    }

}

