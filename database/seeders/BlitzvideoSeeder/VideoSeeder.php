<?php

namespace Database\Seeders\BlitzvideoSeeder;

use Illuminate\Database\Seeder;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Video;
use App\Models\Blitzvideo\Etiqueta;

class VideoSeeder extends Seeder
{
    public function run()
    {
        $canales = Canal::with('user')->get();
        $etiquetas = Etiqueta::all();

        foreach ($canales as $canal) {
            for ($i = 1; $i <= 3; $i++) {
                $video = Video::create([
                    'canal_id' => $canal->id,
                    'titulo' => 'Título del video ' . $i . ' para ' . $canal->nombre,
                    'descripcion' => 'Descripción del video ' . $i . ' para ' . $canal->nombre . ' de ' . $canal->user->name,
                    'link' => 'https://www.Blitzvideo.com/video_' . $i . '_' . $canal->id,
                    'miniatura' => 'https://www.Blitzvideo.com/miniatura' . $i . '_' . $canal->id
                ]);
                $video->etiquetas()->attach($etiquetas->random(3)->pluck('id')->toArray());
            }
        }
    }
}