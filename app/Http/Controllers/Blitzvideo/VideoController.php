<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function MostrarTodosLosVideos(Request $request)
    {
        $videos = $this->obtenerVideosConRelaciones()->take(10);
        return view('video.videos', compact('videos'));
    }

    private function obtenerVideosConRelaciones()
    {
        return Video::with([
            'canal:id,nombre,descripcion,user_id',
            'canal.user:id,name,email',
            'etiquetas:id,nombre',
        ])
        ->withCount([
            'puntuaciones as puntuacion_1' => function ($query) {
                $query->where('valora', 1);
            },
            'puntuaciones as puntuacion_2' => function ($query) {
                $query->where('valora', 2);
            },
            'puntuaciones as puntuacion_3' => function ($query) {
                $query->where('valora', 3);
            },
            'puntuaciones as puntuacion_4' => function ($query) {
                $query->where('valora', 4);
            },
            'puntuaciones as puntuacion_5' => function ($query) {
                $query->where('valora', 5);
            },
            'visitas',
        ])->get()->each(function ($video) {
            $video->promedio_puntuaciones = $video->puntuacion_promedio;
        });
    }

    public function ListarVideosPorNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $videos = $this->obtenerVideosPorNombre($nombre)->take(10);
        return view('video.videos', compact('videos'));
    }

    private function obtenerVideosPorNombre($nombre)
    {
        return Video::with([
            'canal:id,nombre,descripcion,user_id',
            'canal.user:id,name,email',
            'etiquetas:id,nombre',
        ])
        ->withCount([
            'puntuaciones as puntuacion_1' => function ($query) {
                $query->where('valora', 1);
            },
            'puntuaciones as puntuacion_2' => function ($query) {
                $query->where('valora', 2);
            },
            'puntuaciones as puntuacion_3' => function ($query) {
                $query->where('valora', 3);
            },
            'puntuaciones as puntuacion_4' => function ($query) {
                $query->where('valora', 4);
            },
            'puntuaciones as puntuacion_5' => function ($query) {
                $query->where('valora', 5);
            },
            'visitas',
        ])
        ->where('titulo', 'LIKE', '%' . $nombre . '%')
        ->get()
        ->each(function ($video) {
            $video->promedio_puntuaciones = $video->puntuacion_promedio;
        });
    }

    public function MostrarInformacionVideo($idVideo)
    {
        $video = $this->obtenerVideoPorId($idVideo);
        return view('video.video', compact('video'));
    }

    private function obtenerVideoPorId($idVideo)
    {
        $video = Video::with([
            'canal:id,nombre,descripcion,user_id',
            'canal.user:id,name,email,foto',
            'etiquetas:id,nombre',
        ])
        ->withCount([
            'puntuaciones as puntuacion_1' => function ($query) {
                $query->where('valora', 1);
            },
            'puntuaciones as puntuacion_2' => function ($query) {
                $query->where('valora', 2);
            },
            'puntuaciones as puntuacion_3' => function ($query) {
                $query->where('valora', 3);
            },
            'puntuaciones as puntuacion_4' => function ($query) {
                $query->where('valora', 4);
            },
            'puntuaciones as puntuacion_5' => function ($query) {
                $query->where('valora', 5);
            },
            'visitas',
        ])->findOrFail($idVideo);

        $video->promedio_puntuaciones = $video->puntuacion_promedio;

        return $video;
    }

    public function MostrarFormularioSubida()
    {
        $etiquetasController = new EtiquetaController();
        $etiquetas = $etiquetasController->ListarEtiquetas();
        return view('video.subir-video', compact('etiquetas'));
    }

    public function MostrarFormularioEditar($idVideo)
    {
        $etiquetasController = new EtiquetaController();
        $etiquetas = $etiquetasController->ListarEtiquetas();
        $video = $this->obtenerVideoPorId($idVideo);
        return view('video.editar-video', compact('etiquetas', 'video'));
    }
    
    public function EditarVideo(Request $request, $idVideo)
    {
        $this->ValidarEdicionDeVideo($request);

        $video = Video::findOrFail($idVideo);

        if ($request->has('titulo')) {
            $video->titulo = $request->input('titulo');
        }

        if ($request->has('descripcion')) {
            $video->descripcion = $request->input('descripcion');
        }

        if ($request->hasFile('video')) {
            $rutaVideo = $this->GuardarArchivo($request->file('video'), 'videos/' . $video->canal_id);
            $video->link = $this->GenerarUrl($rutaVideo);
        }

        if ($request->hasFile('miniatura')) {
            $rutaMiniatura = $this->GuardarArchivo($request->file('miniatura'), 'miniaturas/' . $video->canal_id);
            $video->miniatura = $this->GenerarUrl($rutaMiniatura);
        }

        $video->save();

        if ($request->has('etiquetas')) {
            $this->AsignarEtiquetas($request, $video->id);
        }

        return redirect()->route('video.editar.formulario', ['id' => $idVideo])->with('success', 'Video editado exitosamente');
    }

    private function ValidarEdicionDeVideo($request)
    {
        $rules = [
            'titulo' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'video' => 'sometimes|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-flv,video/webm|max:120000',
            'miniatura' => 'sometimes|image|max:2048',
        ];

        $this->ValidarRequest($request, $rules);
    }

    public function SubirVideo(Request $request)
    {
        $this->ValidarSubidaDeVideo($request);

        $canalId = $request->input('canal_id');
        if (!$request->hasFile('video') || !$request->hasFile('miniatura')) {
            return redirect()->back()->with('error', 'Debe proporcionar tanto el archivo de video como la miniatura');
        }

        $canal = Canal::findOrFail($canalId);
        $videoData = $this->ProcesarVideo($request->file('video'), $request->file('miniatura'), $canalId);

        $video = $this->CrearNuevoVideo($request, $canal, $videoData);

        if ($request->has('etiquetas')) {
            $this->AsignarEtiquetas($request, $video->id);
        }

        return redirect()->route('video.crear.formulario')->with('success', 'Video subido exitosamente');
    }

    private function ValidarSubidaDeVideo($request)
    {
        $rules = [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-flv,video/webm|max:120000',
            'miniatura' => 'required|image|max:2048',
        ];

        $this->ValidarRequest($request, $rules);
    }

    private function ValidarRequest($request, $rules)
    {
        $request->validate($rules);
    }

    private function ProcesarVideo($videoFile, $miniaturaFile, $canalId)
    {
        $rutaVideo = $this->GuardarArchivo($videoFile, 'videos/' . $canalId);
        $rutaMiniatura = $this->GuardarArchivo($miniaturaFile, 'miniaturas/' . $canalId);

        return [
            'urlVideo' => $this->GenerarUrl($rutaVideo),
            'urlMiniatura' => $this->GenerarUrl($rutaMiniatura),
        ];
    }

    private function GuardarArchivo($archivo, $ruta)
    {
        return $archivo->store($ruta, 's3');
    }

    private function GenerarUrl($ruta)
    {
        return str_replace('minio', env('BLITZVIDEO_HOST'), Storage::disk('s3')->url($ruta));
    }

    private function CrearNuevoVideo($request, $canal, $videoData)
    {
        return Video::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'link' => $videoData['urlVideo'],
            'miniatura' => $videoData['urlMiniatura'],
            'canal_id' => $canal->id,
        ]);
    }

    private function AsignarEtiquetas($request, $videoId)
    {
        $etiquetasController = new EtiquetaController();
        $etiquetasController->AsignarEtiquetas($request, $videoId);
    }

    public function BajaVideo($idVideo)
    {
        try {
            $video = Video::findOrFail($idVideo);
            $video->delete();
            return redirect()->route('video.listar')->with('success', 'Video dado de baja correctamente');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'El video no existe');
        }
    }
}
