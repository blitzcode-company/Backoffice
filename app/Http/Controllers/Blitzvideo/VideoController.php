<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Events\ActividadRegistrada;
use App\Helpers\FFMpegHelper;
use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Etiqueta;
use App\Models\Blitzvideo\Video;
use App\Traits\Paginable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    use Paginable;

    public function MostrarTodosLosVideos(Request $request)
    {
        $videos = $this->obtenerVideosConRelaciones();
        $page = $request->input('page', 1);
        $videos = $this->paginateCollection($videos, 9, $page);
        return view('video.videos', compact('videos'));
    }

    private function obtenerVideosConRelaciones()
    {
        return Video::with([
            'canal:id,nombre,descripcion,user_id',
            'canal.user:id,name,email',
            'etiquetas:id,nombre',
        ])
            ->whereHas('canal.user', function ($query) {
                $query->where('name', '<>', 'Invitado');
            })
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
            ->get()->each(function ($video) {
            $video->promedio_puntuaciones = $video->puntuacion_promedio;
        });
    }

    public function ListarVideosPorNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $page = $request->input('page', 1);
        $videos = $this->obtenerVideosPorNombre($nombre);
        $videos = $this->paginateCollection($videos, 9, $page);
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
        $cambios = [];

        try {
            $cambios = array_merge($cambios, $this->actualizarTitulo($request, $video));
            $cambios = array_merge($cambios, $this->actualizarDescripcion($request, $video));
            $cambios = array_merge($cambios, $this->actualizarVideo($request, $video));
            $cambios = array_merge($cambios, $this->actualizarMiniatura($request, $video));

            $video->save();

            if ($request->has('etiquetas')) {
                $this->AsignarEtiquetas($request, $video->id);
            }

            $this->registrarActividadActualizarVideo($cambios, $video->id, $video->canal_id);

            return redirect()->route('video.editar.formulario', ['id' => $idVideo])->with('success', 'Video editado exitosamente');
        } catch (\Exception $exception) {
            return back()->withInput()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    private function actualizarTitulo(Request $request, Video $video)
    {
        $cambios = [];

        if ($request->has('titulo') && $request->input('titulo') != $video->titulo) {
            $cambios['titulo'] = [
                'anterior' => $video->titulo,
                'nuevo' => $request->input('titulo'),
            ];
            $video->titulo = $request->input('titulo');
        }

        return $cambios;
    }

    private function actualizarDescripcion(Request $request, Video $video)
    {
        $cambios = [];

        if ($request->has('descripcion') && $request->input('descripcion') != $video->descripcion) {
            $cambios['descripcion'] = 'cambiado';
            $video->descripcion = $request->input('descripcion');
        }

        return $cambios;
    }

    private function actualizarVideo(Request $request, Video $video)
    {
        $cambios = [];
    
        if ($request->hasFile('video')) {
            $rutaAnterior = $video->link;
            $rutaVideo = $this->GuardarArchivo($request->file('video'), 'videos/' . $video->canal_id);
            
            $video->link = $this->GenerarUrl($rutaVideo);
            $duracion = $this->obtenerDuracionDeVideo($request->file('video'));
            $video->duracion = $duracion;
            $cambios['video'] = [
                'anterior' => $rutaAnterior,
                'nuevo' => $video->link,
            ];
        }
    
        return $cambios;
    }

    private function actualizarMiniatura(Request $request, Video $video)
    {
        $cambios = [];

        if ($request->hasFile('miniatura')) {
            $rutaAnterior = $video->miniatura;
            $rutaMiniatura = $this->GuardarArchivo($request->file('miniatura'), 'miniaturas/' . $video->canal_id);
            $cambios['miniatura'] = [
                'anterior' => $rutaAnterior,
                'nuevo' => $this->GenerarUrl($rutaMiniatura),
            ];
            $video->miniatura = $this->GenerarUrl($rutaMiniatura);
        }

        return $cambios;
    }

    private function registrarActividadActualizarVideo(array $cambios, $videoId, $canalId)
    {
        $detalles = "ID video: $videoId; ID Canal: $canalId;";

        foreach ($cambios as $campo => $valor) {
            if ($campo === 'titulo') {
                $detalles .= "{$valor['anterior']} -> {$valor['nuevo']}; ";
            } elseif ($campo === 'miniatura') {
                $detalles .= "{$valor['anterior']} -> {$valor['nuevo']}; ";
            } elseif ($campo === 'video') {
                $detalles .= "{$valor['anterior']} -> {$valor['nuevo']}; ";
            } else {
                $detalles .= ucfirst($campo) . ': ' . $valor . '; ';
            }
        }
        event(new ActividadRegistrada('Actualización de video', $detalles));
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
        $duracion = $this->obtenerDuracionDeVideo($request->file('video'));
        $video = $this->CrearNuevoVideo($request, $canal, $videoData, $duracion);
        if ($request->has('etiquetas')) {
            $this->AsignarEtiquetas($request, $video->id);
        }
        $this->registrarActividadSubirVideo($video);

        return redirect()->route('video.crear.formulario')->with('success', 'Video subido exitosamente');
    }

    private function obtenerDuracionDeVideo($videoFile)
    {
        $ffmpeg = FFMpegHelper::crearFFMpeg();
        $video = $ffmpeg->open($videoFile->getRealPath());
        $duracionTotalDelVideo = $video->getStreams()->videos()->first()->get('duration');

        return $duracionTotalDelVideo;
    }

    private function registrarActividadSubirVideo($video)
    {
        $detalles = sprintf(
            'ID video: %d; Título: %s; ID canal: %d;',
            $video->id,
            $video->titulo,
            $video->canal_id
        );
        event(new ActividadRegistrada('Nuevo video subido', $detalles));
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

    private function CrearNuevoVideo(Request $request, Canal $canal, array $videoData, $duracion)
    {
        return Video::create([
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'link' => $videoData['urlVideo'],
            'miniatura' => $videoData['urlMiniatura'],
            'canal_id' => $canal->id,
            'duracion' => $duracion,
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
            $tituloVideo = $video->titulo;
            $video->delete();
            $this->registrarActividadBajaVideo($idVideo, $tituloVideo);
            return redirect()->route('video.listar')->with('success', 'Video dado de baja correctamente');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'El video no existe');
        }
    }

    private function registrarActividadBajaVideo($idVideo, $tituloVideo)
    {
        $detalles = "ID video: $idVideo; Título: $tituloVideo";
        event(new ActividadRegistrada('Baja de video', $detalles));
    }

    public function MostrarEtiquetasConConteoVideos()
    {
        $etiquetas = Etiqueta::on('blitzvideo')
            ->withCount(['videos' => function ($query) {
                $query->whereHas('canal.user', function ($subQuery) {
                    $subQuery->where('name', '<>', 'Invitado');
                });
            }])
            ->orderBy('id', 'desc')
            ->get();

        return view('video.etiquetas-videos', compact('etiquetas'));
    }

    public function listarVideosPorEtiqueta($id)
    {
        try {
            $etiqueta = Etiqueta::findOrFail($id);

            $videos = $etiqueta->videos()
                ->whereDoesntHave('canal.user', function ($query) {
                    $query->where('name', 'Invitado');
                })
                ->paginate(9);

            return view('video.listar-por-etiqueta', compact('videos', 'etiqueta'));
        } catch (\Exception $e) {
            return redirect()->route('video.etiquetas')->with('error', 'No se pudieron listar los videos.');
        }
    }

}
