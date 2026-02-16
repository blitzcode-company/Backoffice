<?php

namespace Database\Seeders\BlitzvideoSeeder;

use Illuminate\Database\Seeder;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Video;
use App\Models\Blitzvideo\Stream;

class StreamSeeder extends Seeder
{
    public function run()
    {
        $canales = Canal::with('user')->get();

        foreach ($canales as $index => $canal) {
            // Crear un stream activo (Live) para algunos canales (pares)
            if ($index % 2 == 0) {
                $videoLive = Video::create([
                    'canal_id'    => $canal->id,
                    'titulo'      => '🔴 EN VIVO: ' . $canal->nombre,
                    'descripcion' => 'Transmisión en directo de ' . $canal->user->name,
                    'link'        => 'stream_live_' . uniqid(),
                    'miniatura'   => null,
                    'duracion'    => 0,
                    'bloqueado'   => false,
                    'acceso'      => 'publico',
                    'estado'      => 'VIDEO', // Estado para que sea visible si se busca como video
                ]);

                $streamLive = new Stream();
                $streamLive->video_id = $videoLive->id;
                $streamLive->stream_programado = now();
                $streamLive->max_viewers = rand(100, 5000);
                $streamLive->total_viewers = rand(5000, 20000);
                $streamLive->activo = true;
                $streamLive->save();
            }

            // Crear un stream programado (Offline) para todos
            $videoProgramado = Video::create([
                'canal_id'    => $canal->id,
                'titulo'      => 'Próximo Stream: Evento Especial',
                'descripcion' => 'No te pierdas el próximo evento en el canal de ' . $canal->user->name,
                'link'        => 'stream_scheduled_' . uniqid(),
                'miniatura'   => null,
                'duracion'    => 0,
                'bloqueado'   => false,
                'acceso'      => 'publico',
                'estado'      => 'PROGRAMADO',
            ]);

            $streamProgramado = new Stream();
            $streamProgramado->video_id = $videoProgramado->id;
            $streamProgramado->stream_programado = now()->addDays(rand(1, 3));
            $streamProgramado->max_viewers = 0;
            $streamProgramado->total_viewers = 0;
            $streamProgramado->activo = false;
            $streamProgramado->save();
        }
    }
}