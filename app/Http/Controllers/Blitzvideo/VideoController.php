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

class VideoController extends Controller
{
    use Paginable;

    public function MostrarTodosLosVideos(Request $request)
    {
        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();
        $videos = $this->obtenerVideosConRelaciones();
        foreach ($videos as $video) {
            $video->miniatura = $this->obtenerUrlArchivo($video->miniatura, $host, $bucket);
            $video->link      = $this->obtenerUrlArchivo($video->link, $host, $bucket);
        }
        $page   = $request->input('page', 1);
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
        $page   = $request->input('page', 1);
        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();
        $videos = $this->obtenerVideosPorNombre($nombre);
        foreach ($videos as $video) {
            $video->miniatura = $this->obtenerUrlArchivo($video->miniatura, $host, $bucket);
            $video->link      = $this->obtenerUrlArchivo($video->link, $host, $bucket);
        }
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
        $video            = $this->obtenerVideoPorId($idVideo);
        $host             = $this->obtenerHostMinio();
        $bucket           = $this->obtenerBucket();
        $video->miniatura = $this->obtenerUrlArchivo($video->miniatura, $host, $bucket);
        $video->link      = $this->obtenerUrlArchivo($video->link, $host, $bucket);
        if ($video->canal && $video->canal->user) {
            $video->canal->user->foto = $this->obtenerUrlArchivo($video->canal->user->foto, $host, $bucket);
        }
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

    public function MostrarFormularioSubida()
    {
        $etiquetasController = new EtiquetaController();
        $etiquetas           = $etiquetasController->ListarEtiquetas();
        return view('video.subir-video', compact('etiquetas'));
    }

    public function MostrarFormularioEditar($idVideo)
    {
        $etiquetasController = new EtiquetaController();
        $etiquetas           = $etiquetasController->ListarEtiquetas();
        $video               = $this->obtenerVideoPorId($idVideo);
        $host                = $this->obtenerHostMinio();
        $bucket              = $this->obtenerBucket();
        $video->miniatura    = $this->obtenerUrlArchivo($video->miniatura, $host, $bucket);
        $video->link         = $this->obtenerUrlArchivo($video->link, $host, $bucket);
        if ($video->canal && $video->canal->user) {
            $video->canal->user->foto = $this->obtenerUrlArchivo($video->canal->user->foto, $host, $bucket);
        }
        return view('video.editar-video', compact('etiquetas', 'video'));
    }

    public function EditarVideo(Request $request, $idVideo)
    {
        $this->ValidarEdicionDeVideo($request);

        $video   = Video::findOrFail($idVideo);
        $cambios = [];

        try {
            $cambios = array_merge($cambios, $this->actualizarTitulo($request, $video));
            $cambios = array_merge($cambios, $this->actualizarDescripcion($request, $video));
            $cambios = array_merge($cambios, $this->actualizarVideo($request, $video));
            $cambios = array_merge($cambios, $this->actualizarMiniatura($request, $video));
            $cambios = array_merge($cambios, $this->actualizarAcceso($request, $video));

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
                'nuevo'    => $request->input('titulo'),
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
            $video->descripcion     = $request->input('descripcion');
        }

        return $cambios;
    }

    private function actualizarVideo(Request $request, Video $video)
    {
        $cambios = [];

        if ($request->hasFile('video')) {
            $rutaAnterior = $video->link;
            $rutaVideo    = $this->GuardarArchivo($request->file('video'), 'videos/' . $video->canal_id);
            $video->link  = $this->GenerarUrl($rutaVideo);
            if ($request->has('duracion')) {
                $video->duracion = $request->input('duracion');
            } else {
                $duracion        = $this->obtenerDuracionDeVideo($request->file('video'));
                $video->duracion = $duracion;
            }
            $video->duracion  = $duracion;
            $cambios['video'] = [
                'anterior' => $rutaAnterior,
                'nuevo'    => $video->link,
            ];
        }

        return $cambios;
    }

    private function actualizarMiniatura(Request $request, Video $video)
    {
        $cambios = [];

        if ($request->hasFile('miniatura')) {
            $rutaAnterior         = $video->miniatura;
            $rutaMiniatura        = $this->GuardarArchivo($request->file('miniatura'), 'miniaturas/' . $video->canal_id);
            $cambios['miniatura'] = [
                'anterior' => $rutaAnterior,
                'nuevo'    => $this->GenerarUrl($rutaMiniatura),
            ];
            $video->miniatura = $this->GenerarUrl($rutaMiniatura);
        }

        return $cambios;
    }

    private function actualizarAcceso(Request $request, Video $video)
    {
        $cambios = [];

        if ($request->has('acceso') && $request->input('acceso') != $video->acceso) {
            $cambios['acceso'] = [
                'anterior' => $video->acceso,
                'nuevo'    => $request->input('acceso'),
            ];
            $video->acceso = $request->input('acceso');
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
            } elseif ($campo === 'acceso') {
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
            'titulo'      => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'video'       => 'sometimes|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-flv,video/webm|max:120000',
            'miniatura'   => 'sometimes|image|max:2048',
            'acceso'      => 'sometimes|string|max:255',
        ];

        $this->ValidarRequest($request, $rules);
    }

    public function SubirVideo(Request $request)
    {
        $this->ValidarSubidaDeVideo($request);

        $canalId = $request->input('canal_id');
        if (! $request->hasFile('video') || ! $request->hasFile('miniatura')) {
            return redirect()->back()->with('error', 'Debe proporcionar tanto el archivo de video como la miniatura');
        }

        $canal     = Canal::findOrFail($canalId);
        $videoData = $this->ProcesarVideo($request->file('video'), $request->file('miniatura'), $canalId);
        $duracion  = $request->input('duracion', null);
        if (is_null($duracion)) {
            $duracion = $this->obtenerDuracionDeVideo($request->file('video'));
        }
        $video = $this->CrearNuevoVideo($request, $canal, $videoData, $duracion);
        if ($request->has('etiquetas')) {
            $this->AsignarEtiquetas($request, $video->id);
        }
        $this->registrarActividadSubirVideo($video);

        return redirect()->route('video.crear.formulario')->with('success', 'Video subido exitosamente');
    }

    private function obtenerDuracionDeVideo($videoFile)
    {
        $ffmpeg                = FFMpegHelper::crearFFMpeg();
        $video                 = $ffmpeg->open($videoFile->getRealPath());
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
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'video'       => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-flv,video/webm|max:120000',
            'miniatura'   => 'required|image|max:2048',
            'acceso'      => 'required|in:publico,privado',
        ];

        $this->ValidarRequest($request, $rules);
    }

    private function ValidarRequest($request, $rules)
    {
        $request->validate($rules);
    }

    private function ProcesarVideo($videoFile, $miniaturaFile, $canalId)
    {
        $rutaVideo     = $this->GuardarArchivo($videoFile, 'videos/' . $canalId);
        $rutaMiniatura = $this->GuardarArchivo($miniaturaFile, 'miniaturas/' . $canalId);
        return [
            'urlVideo'     => $rutaVideo,
            'urlMiniatura' => $rutaMiniatura,
        ];
    }

    private function GuardarArchivo($archivo, $ruta)
    {
        return $archivo->store($ruta, 's3');
    }

    private function CrearNuevoVideo(Request $request, Canal $canal, array $videoData, $duracion)
    {
        return Video::create([
            'titulo'      => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'link'        => $videoData['urlVideo'],
            'miniatura'   => $videoData['urlMiniatura'],
            'canal_id'    => $canal->id,
            'duracion'    => $duracion,
            'acceso'      => $request->input('acceso', 'publico'),
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
            $video       = Video::findOrFail($idVideo);
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

    public function MostrarEtiquetasConConteoVideos(Request $request)
    {
        $etiquetas = Etiqueta::on('blitzvideo')
            ->withCount(['videos' => function ($query) {
                $query->whereHas('canal.user', function ($subQuery) {
                    $subQuery->where('name', '<>', 'Invitado');
                });
            }])
            ->orderBy('id', 'desc')
            ->paginate(8);

        return view('video.etiquetas-videos', compact('etiquetas'));
    }

    public function listarVideosPorEtiqueta($id)
    {
        try {
            $etiqueta = Etiqueta::findOrFail($id);
            $videos   = $etiqueta->videos()
                ->whereDoesntHave('canal.user', function ($query) {
                    $query->where('name', 'Invitado');
                })
                ->with(['canal.user'])
                ->paginate(9);
            $host   = $this->obtenerHostMinio();
            $bucket = $this->obtenerBucket();
            foreach ($videos as $video) {
                $video->miniatura = $this->obtenerUrlArchivo($video->miniatura, $host, $bucket);
                $video->link      = $this->obtenerUrlArchivo($video->link, $host, $bucket);
                if ($video->canal && $video->canal->user) {
                    $video->canal->user->foto = $this->obtenerUrlArchivo($video->canal->user->foto, $host, $bucket);
                }
            }
            return view('video.listar-por-etiqueta', compact('videos', 'etiqueta'));
        } catch (\Exception $e) {
            return redirect()->route('video.etiquetas')->with('error', 'No se pudieron listar los videos.');
        }
    }

    public function ListarVideosPorCanal($canalId, Request $request)
    {
        $page   = $request->input('page', 1);
        $titulo = $request->input('titulo');
        $videos = $this->obtenerVideosPorCanal($canalId, $titulo);
        $videos = $this->paginateCollection($videos, 9, $page);
        return view('video.listar-por-canal', compact('videos', 'canalId'));
    }

    private function obtenerVideosPorCanal($canalId, $titulo = null)
    {
        $query = Video::with([
            'canal:id,nombre,descripcion,user_id',
            'canal.user:id,name,email',
            'etiquetas:id,nombre',
        ])
            ->where('canal_id', $canalId)
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
            ]);
        if (! empty($titulo)) {
            $query->where('titulo', 'like', '%' . $titulo . '%');
        }

        return $query->get()->each(function ($video) {
            $video->promedio_puntuaciones = $video->puntuacion_promedio;
        });
    }

    public function bloquearVideo(Request $request, $id)
    {
        $video  = $this->cambiarEstadoVideo($id, true);
        $motivo = $request->input('motivo');
        $this->registrarYEnviarCorreoDeBloqueo($video, 'Bloqueo de video', $motivo);
        $this->notificacionBloqueoVideo($video, $motivo);
        return redirect()->route('video.detalle', ['id' => $id])->with('success', 'El video ha sido bloqueado exitosamente.');
    }

    public function desbloquearVideo($id)
    {
        $video = $this->cambiarEstadoVideo($id, false);
        $this->registrarActividadBloqueoDesbloqueo($video, 'Desbloqueo de video');
        $this->notificacionDesbloqueoVideo($video);
        return redirect()->route('video.detalle', ['id' => $id])->with('success', 'El video ha sido desbloqueado exitosamente.');
    }

    private function cambiarEstadoVideo($id, $estado)
    {
        $video            = Video::findOrFail($id);
        $video->bloqueado = $estado;
        $video->save();

        return $video;
    }

    private function registrarYEnviarCorreoDeBloqueo($video, $tipoActividad, $motivo)
    {
        $this->registrarActividadBloqueoDesbloqueo($video, $tipoActividad);

        $usuario        = $video->canal->user;
        $titulo_video   = $video->titulo;
        $mailController = new MailController();
        $mailController->correoBloqueoDeVideo($usuario->email, $usuario->name, $titulo_video, $motivo);
    }

    private function notificacionBloqueoVideo($video, $motivo)
    {
        $notificacionController = new NotificacionController();
        $notificacionController->crearNotificacionDeBloqueoDeVideo($video, $motivo);
    }

    private function registrarActividadBloqueoDesbloqueo($video, $tipoActividad)
    {
        $detalles = "ID video: $video->id; Título: $video->titulo";
        event(new ActividadRegistrada($tipoActividad, $detalles));
    }

    private function notificacionDesbloqueoVideo($video)
    {
        $notificacionController = new NotificacionController();
        $notificacionController->crearNotificacionDeDesbloqueoDeVideo($video);
    }

}
