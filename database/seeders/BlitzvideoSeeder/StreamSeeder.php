<?php

namespace Database\Seeders\BlitzvideoSeeder;

use Illuminate\Database\Seeder;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Video;
use App\Models\Blitzvideo\Stream;
use Illuminate\Support\Facades\Schema;

class StreamSeeder extends Seeder
{
    public function run()
    {
        $canales = Canal::with('user')->get();

        foreach ($canales as $index => $canal) {
            if ($index % 2 == 0) {
                $videoLive = Video::create([
                    'canal_id'    => $canal->id,
                    'titulo'      => '🔴 EN VIVO: ' . $canal->nombre,
                    'descripcion' => 'Transmisión en directo de ' . $canal->user->name,
                    'link'        => 'stream_live_' . uniqid(),
                    'miniatura'   => 'https://via.placeholder.com/640x360.png?text=Live+Stream',
                    'duracion'    => 0,
                    'bloqueado'   => false,
                    'acceso'      => 'publico',
                    'estado'      => 'VIDEO',
                ]);

                $streamLive = new Stream();
                if (Schema::hasColumn('streams', 'video_id')) {
                    $streamLive->video_id = $videoLive->id;
                }
                if (Schema::hasColumn('streams', 'canal_id')) {
                    $streamLive->canal_id = $canal->id;
                }
                $streamLive->stream_programado = now();
                $streamLive->max_viewers = rand(100, 5000);
                $streamLive->total_viewers = rand(5000, 20000);
                $streamLive->activo = true;
                $streamLive->save();
            }
            $videoProgramado = Video::create([
                'canal_id'    => $canal->id,
                'titulo'      => 'Próximo Stream: Evento Especial',
                'descripcion' => 'No te pierdas el próximo evento en el canal de ' . $canal->user->name,
                'link'        => 'stream_scheduled_' . uniqid(),
                'miniatura'   => 'https://via.placeholder.com/640x360.png?text=Scheduled+Stream',
                'duracion'    => 0,
                'bloqueado'   => false,
                'acceso'      => 'publico',
                'estado'      => 'PROGRAMADO',
            ]);

            $streamProgramado = new Stream();
            if (Schema::hasColumn('streams', 'video_id')) {
                $streamProgramado->video_id = $videoProgramado->id;
            }
            if (Schema::hasColumn('streams', 'canal_id')) {
                $streamProgramado->canal_id = $canal->id;
            }
            $streamProgramado->stream_programado = now()->addDays(rand(1, 3));
            $streamProgramado->max_viewers = 0;
            $streamProgramado->total_viewers = 0;
            $streamProgramado->activo = false;
            $streamProgramado->save();
        }
    }
}