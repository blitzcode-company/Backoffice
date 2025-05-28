<?php
namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Stream;
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
        return view('stream.editar-stream', compact('stream'));
    }
    public function MostrarStream($id)
    {
        $stream = Stream::findOrFail($id);
        $link   = env('STREAM_BASE_LINK') . $stream->canal->stream_key . ".m3u8";
        return view('stream.stream', compact('stream', 'link'));
    }
    public function ListarStreams(Request $request)
    {
        $streams = Stream::all();
        $page    = $request->input('page', 1);
        $streams = $this->paginateCollection($streams, 9, $page);
        return view('stream.streams', compact('streams'));
    }

    public function ListarStreamsPorNombre(Request $request)
    {
        $nombre  = $request->input('nombre');
        $page    = $request->input('page', 1);
        $streams = Stream::where('titulo', 'like', '%' . $nombre . '%')->get();
        $streams = $this->paginateCollection($streams, 9, $page);
        return view('stream.streams', compact('streams'));
    }

    public function CrearStream(Request $request)
    {
        $canal_id            = $request->input('canal_id');
        $miniatura           = $this->subirAMinio($request->file('miniatura'), 'miniaturas-streams/' . $canal_id);
        $stream              = new Stream();
        $stream->titulo      = $request->input('titulo');
        $stream->descripcion = $request->input('descripcion');
        $stream->miniatura   = $miniatura['url'];
        $stream->canal_id    = $canal_id;
        $stream->save();
        return redirect()->route('stream.crear.formulario')->with('success', 'Stream creado exitosamente.');
    }

    public function EditarStream(Request $request, $id)
    {
        $stream = Stream::findOrFail($id);
        if ($request->has('titulo')) {
            $stream->titulo = $request->input('titulo');
        }
        if ($request->has('descripcion')) {
            $stream->descripcion = $request->input('descripcion');
        }
        if ($request->hasFile('miniatura')) {
            if ($stream->miniatura) {
                $this->EliminarMiniaturaStream($stream);
            }
            $miniatura         = $this->subirAMinio($request->file('miniatura'), 'miniaturas-streams/' . $stream->canal_id);
            $stream->miniatura = $miniatura['url'];
        }
        $stream->save();
        return redirect()->route('stream.editar.formulario', ['id' => $id])->with('success', 'Stream editado exitosamente.');
    }

    private function SubirAMinio($archvio, $ruta)
    {
        $ruta = $this->GuardarArchivo($archvio, $ruta);
        return $ruta;
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
        if ($stream->miniatura) {
            $this->EliminarMiniaturaStream($stream);
        }
        $stream->delete();
        return redirect()->route('stream.streams')->with('success', 'Stream eliminado exitosamente.');
    }

    private function EliminarMiniaturaStream($stream)
    {
        $archivo      = basename(parse_url($stream->miniatura, PHP_URL_PATH));
        $rutaAnterior = 'miniaturas-streams/' . $stream->canal_id . '/' . $archivo;
        Storage::disk('s3')->delete($rutaAnterior);
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

}
