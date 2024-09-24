<?php

namespace App\Events;

class ActividadRegistrada
{
    public $nombre;
    public $detalles;

    public function __construct($nombre, $detalles)
    {
        $this->nombre = $nombre;
        $this->detalles = $detalles;
    }
}

