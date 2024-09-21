<?php

namespace Database\Seeders\BlitzvideoSeeder;

use Illuminate\Database\Seeder;
use App\Models\Blitzvideo\Etiqueta;

class EtiquetaSeeder extends Seeder
{
    public function run()
    {
        $etiquetas = [
            'Belleza', 'Entretenimiento', 'Educación', 'Moda', 'Videojuegos',
            'Vlogs', 'Estilo De Vida', 'Cocina', 'Gastronomía', 'Viajes',
            'Aventuras', 'Música', 'Tecnología', 'Deporte', 'Fitness'
        ];

        foreach ($etiquetas as $nombre) {
            Etiqueta::create(['nombre' => $nombre]);
        }
    }
}