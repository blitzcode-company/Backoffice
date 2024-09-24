<?php

namespace App\Listeners;

use App\Events\ActividadRegistrada;
use App\Models\Actividad;
use Illuminate\Support\Facades\Auth;

class RegistroActividadListener
{
    public function handle(ActividadRegistrada $event)
    {
        $actividad = new Actividad();
        $actividad->user_id = Auth::id();
        $actividad->nombre = $event->nombre;
        $actividad->detalles = $event->detalles;
        $actividad->save();
    }
}

