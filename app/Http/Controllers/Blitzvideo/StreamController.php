<?php
namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Stream;
use App\Models\Blitzvideo\Video;
use App\Models\Blitzvideo\Canal;
use App\Traits\Paginable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StreamController extends Controller
{
    use Paginable;

    public function MostrarFormularioSubidaStream()
    {
        return view('stream.subir-stream');
    }

    public function MostrarFormularioEditarStream($id)
    {
        $stream = Stream::findOrFail($id);
        if ($stream->video_id) {
            $video = Video::find($stream->video_id);
            if ($video) {
                $stream->titulo = $video->titulo;
                $stream->descripcion = $video->descripcion;
                $stream->miniatura = $this->obtenerUrlArchivo($video->miniatura, $this->obtenerHostMinio(), $this->obtenerBucket());
            }
        }
        return view('stream.editar-stream', compact('stream'));
    }
    public function MostrarStream($id)
    {
        $stream = Stream::join('videos', 'streams.video_id', '=', 'videos.id')
            ->select('streams.*', 'videos.titulo', 'videos.descripcion', 'videos.miniatura', 'videos.canal_id')
            ->where('streams.id', $id)
            ->firstOrFail();

        $stream->load('canal.user');

        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();

        if ($stream->canal && $stream->canal->user) {
            $stream->canal->user->foto = $this->obtenerUrlArchivo($stream->canal->user->foto, $host, $bucket);
        }

        $link = env('STREAM_BASE_LINK') . $stream->canal->stream_key . ".m3u8";
        return view('stream.stream', compact('stream', 'link'));
    }
    public function ListarStreams(Request $request)
    {
        $page    = $request->input('page', 1);
        $streamsQuery = Stream::join('videos', 'streams.video_id', '=', 'videos.id')
            ->select('streams.*', 'videos.titulo', 'videos.descripcion', 'videos.miniatura', 'videos.canal_id')
            ->with('canal.user')
            ->orderBy('streams.created_at', 'desc');

        $streams = $this->paginateBuilder($streamsQuery, 9, $page);
        
        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();

        foreach ($streams as $stream) {
            $stream->miniatura = $this->obtenerUrlArchivo($stream->miniatura, $host, $bucket);
        }

        return view('stream.streams', compact('streams'));
    }

    public function ListarStreamsPorNombre(Request $request)
    {
        $nombre  = $request->input('nombre');
        $page    = $request->input('page', 1);
        
        $streamsQuery = Stream::join('videos', 'streams.video_id', '=', 'videos.id')
            ->select('streams.*', 'videos.titulo', 'videos.descripcion', 'videos.miniatura', 'videos.canal_id')
            ->with('canal.user')
            ->where('videos.titulo', 'like', '%' . $nombre . '%')
            ->orderBy('streams.created_at', 'desc');

        $streams = $this->paginateBuilder($streamsQuery, 9, $page);

        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();

        foreach ($streams as $stream) {
            $stream->miniatura = $this->obtenerUrlArchivo($stream->miniatura, $host, $bucket);
        }

        return view('stream.streams', compact('streams'));
    }

    public function CrearStream(Request $request)
    {
        $canal_id            = $request->input('canal_id');
        $miniaturaUrl        = $this->GuardarArchivo($request->file('miniatura'), 'miniaturas-streams/' . $canal_id);
        $video = Video::create([
            'titulo'      => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'miniatura'   => $miniaturaUrl,
            'canal_id'    => $canal_id,
            'estado'      => 'PROGRAMADO',
            'acceso'      => 'publico',
            'link'        => 'stream_' . uniqid(),
        ]);

        $stream              = new Stream();
        $stream->video_id    = $video->id;
        $stream->activo      = false;
        $stream->forceFill(['canal_id' => $canal_id]);
        
        $stream->save();

        return redirect()->route('stream.crear.formulario')->with('success', 'Stream creado exitosamente.');
    }

    public function EditarStream(Request $request, $id)
    {
        $stream = Stream::findOrFail($id);
        $video  = Video::findOrFail($stream->video_id);

        if ($request->has('titulo')) {
            $video->titulo = $request->input('titulo');
        }
        if ($request->has('descripcion')) {
            $video->descripcion = $request->input('descripcion');
        }
        if ($request->hasFile('miniatura')) {
            if ($video->miniatura) {
                $this->EliminarMiniaturaStream($video->miniatura);
            }
            $ruta = $this->GuardarArchivo($request->file('miniatura'), 'miniaturas-streams/' . $video->canal_id);
            $video->miniatura = $ruta;
        }
        $video->save();       
        return redirect()->route('stream.editar.formulario', ['id' => $id])->with('success', 'Stream editado exitosamente.');
    }

    private function GuardarArchivo($archivo, $ruta)
    {
        return $archivo->store($ruta, 's3');
    }

    public function EliminarStream($id)
    {
        $stream  = Stream::findOrFail($id);
        $archivo = $this->ObtenerArchivoEnMinio($stream);
        if ($archivo) {
            Storage::disk('s3')->delete($archivo);
        }
        if ($stream->video_id) {
            $video = Video::find($stream->video_id);
            if ($video) {
                if ($video->miniatura) {
                    $this->EliminarMiniaturaStream($video->miniatura);
                }
                $video->delete();
            } else {
                $stream->delete();
            }
        } else {
            $stream->delete();
        }
        
        return redirect()->route('stream.streams')->with('success', 'Stream eliminado exitosamente.');
    }

    private function EliminarMiniaturaStream($rutaMiniatura)
    {
        if ($rutaMiniatura) {
            Storage::disk('s3')->delete($rutaMiniatura);
        }
    }

    private function ObtenerArchivoEnMinio($stream)
    {
        $directorioMinio = 'streams';
        $streamKey       = $stream->canal->stream_key;
        $patron          = '/^' . preg_quote("{$directorioMinio}/{$streamKey}-", '/') . '\d+\.\w+$/';
        $archivo         = collect(Storage::disk('s3')->files($directorioMinio))
            ->first(fn($archivo) => preg_match($patron, $archivo));
        return $archivo ?: null;
    }

    private function obtenerHostMinio()
    {
        return str_replace('minio', env('BLITZVIDEO_HOST'), env('AWS_ENDPOINT')) . '/';
    }

    private function obtenerBucket()
    {
        return env('AWS_BUCKET') . '/';
    }

    private function obtenerUrlArchivo($rutaRelativa, $host, $bucket)
    {
        if (! $rutaRelativa) {
            return null;
        }
        if (str_starts_with($rutaRelativa, $host . $bucket)) {
            return $rutaRelativa;
        }
        if (filter_var($rutaRelativa, FILTER_VALIDATE_URL)) {
            return $rutaRelativa;
        }
        return $host . $bucket . $rutaRelativa;
    }
}
