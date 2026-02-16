<?php
namespace App\Http\Controllers\Blitzvideo;

use App\Events\ActividadRegistrada;
use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Video;
use App\Traits\Paginable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CanalController extends Controller
{
    private const ROUTE_LISTAR_CANALES = 'canal.listar';
    private const ROUTE_CREAR_CANAL    = 'canal.crear.formulario';
    private const ROUTE_UPDATE_CANAL   = 'canal.editar.formulario';
    private const USUARIO_EXCLUIDO     = 'Invitado';

    use Paginable;

    public function ListarCanales(Request $request)
    {
        $canalesQuery = Canal::with(['user:id,name,foto'])
            ->whereHas('user', function ($query) {
                $query->where('name', '!=', self::USUARIO_EXCLUIDO);
            })
            ->withCount(['videos' => function ($query) {
                $query->whereIn('estado', ['VIDEO', 'FINALIZADO']);
            }, 'suscriptores' => function ($query) {
                $query->whereNull('suscribe.deleted_at');
            }])
            ->orderBy('id', 'desc');
        $canales = $this->paginateBuilder($canalesQuery, 6, $request->input('page', 1));
        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();
        foreach ($canales as $canal) {
            if ($canal->user) {
                $canal->user->foto = $this->obtenerUrlArchivo($canal->user->foto, $host, $bucket);
            }
            $canal->portada = $this->obtenerUrlArchivo($canal->portada, $host, $bucket);
        }
        return view('canales.listar-canales', compact('canales'));
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

    public function ListarCanalesPorId($id)
    {
        $canal = Canal::with(['user:id,name,foto'])
            ->where('id', $id)
            ->whereHas('user', function ($query) {
                $query->where('name', '!=', self::USUARIO_EXCLUIDO);
            })
            ->withCount(['videos' => function ($query) {
                $query->whereIn('estado', ['VIDEO', 'FINALIZADO']);
            }, 'suscriptores' => function ($query) {
                $query->whereNull('suscribe.deleted_at');
            }])
            ->firstOrFail();
        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();
        if ($canal->user) {
            $canal->user->foto = $this->obtenerUrlArchivo($canal->user->foto, $host, $bucket);
        }
        $canal->portada = $this->obtenerUrlArchivo($canal->portada, $host, $bucket);
        return view('canales.canal', compact('canal'));
    }

    public function ListarCanalesPorNombre(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'nullable|string|max:100',
        ]);
        $nombre = $request->query('nombre');
        $query = Canal::with('user:id,name,foto')
            ->whereHas('user', function ($query) {
                $query->where('name', '!=', self::USUARIO_EXCLUIDO);
            })
            ->withCount(['videos' => function ($query) {
                $query->whereIn('estado', ['VIDEO', 'FINALIZADO']);
            }, 'suscriptores' => function ($query) {
                $query->whereNull('suscribe.deleted_at');
            }]);
        if ($nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }
        $canales = $this->paginateBuilder($query, 10, $request->input('page', 1));
        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();
        foreach ($canales as $canal) {
            if ($canal->user) {
                $canal->user->foto = $this->obtenerUrlArchivo($canal->user->foto, $host, $bucket);
            }
            $canal->portada = $this->obtenerUrlArchivo($canal->portada, $host, $bucket);
        }
        return view('canales.listar-canales', compact('canales'));
    }
    

    public function ListarVideosDeCanal(Request $request, $canalId)
    {
        $page = $request->input('page', 1);
        $videosQuery = Video::where('canal_id', $canalId)
            ->whereIn('estado', ['VIDEO', 'FINALIZADO']);
        
        $videos = $this->paginateBuilder($videosQuery, 9, $page);
        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();
        foreach ($videos as $video) {
            $video->miniatura = $this->obtenerUrlArchivo($video->miniatura, $host, $bucket);
        }
        return view('canales.videos', compact('videos'));
    }
    
    public function MostrarFormularioCrearCanal()
    {
        return view('canales.crear-canal');
    }

    public function MostrarFormularioEditarCanal($id)
    {
        $canal = Canal::with(['user:id,name,foto'])
            ->where('id', $id)
            ->whereHas('user', function ($query) {
                $query->where('name', '!=', self::USUARIO_EXCLUIDO);
            })
            ->withCount(['videos' => function ($query) {
                $query->whereIn('estado', ['VIDEO', 'FINALIZADO']);
            }])
            ->firstOrFail();

        return view('canales.editar-canal', compact('canal'));
    }

    public function CrearCanal(Request $request)
    {
        try {
            $userId         = $request->input('userId');
            $usuario        = User::findOrFail($userId);
            $canalExistente = Canal::where('user_id', $userId)->first();
            if ($canalExistente) {
                return redirect()->back()->withErrors(['message' => 'El usuario ya tiene un canal']);
            }
            $datosValidados = $this->ValidarDatos($request);
            $canal          = $this->CrearNuevoCanal($datosValidados, $userId);
            $this->GuardarCanal($canal);
            $this->GuardarPortada($request, $canal);
            $canal->save();
            $this->registrarActividadCrearCanal($canal, $usuario);
            return redirect()->route(self::ROUTE_CREAR_CANAL)->with('success', 'Canal creado correctamente');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['message' => 'Usuario no encontrado']);
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['message' => 'Ocurrió un error al crear el canal, por favor inténtalo de nuevo más tarde']);
        }
    }

    private function ValidarDatos(Request $request)
    {
        return $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'portada'     => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
        ]);
    }

    private function CrearNuevoCanal(array $datosValidados, $userId)
    {
        return new Canal([
            'nombre'      => $datosValidados['nombre'],
            'descripcion' => $datosValidados['descripcion'] ?? null,
            'user_id'     => $userId,
            'stream_key'  => bin2hex(random_bytes(16)),
        ]);
    }

    private function GuardarPortada(Request $request, Canal $canal)
    {
        if ($request->hasFile('portada')) {
            $portada        = $request->file('portada');
            $folderPath     = 'portadas/' . $canal->id;
            $rutaPortada    = $portada->store($folderPath, 's3');
            $canal->portada = $rutaPortada;
        }
    }

    private function GuardarCanal(Canal $canal)
    {
        return $canal->save();
    }

    private function registrarActividadCrearCanal(Canal $canal, User $usuario)
    {
        $detallesActividad = sprintf(
            "Nombre del canal: %s; ID del canal: %d; ID del usuario: %d; Nombre del usuario: %s;",
            $canal->nombre,
            $canal->id,
            $usuario->id,
            $usuario->name
        );
        event(new ActividadRegistrada('Creación de canal', $detallesActividad));
    }

    public function darDeBajaCanal($canalId, Request $request)
    {
        $motivo = $request->input('motivo');
        try {
            $canal   = Canal::findOrFail($canalId);
            $usuario = $canal->user;
            $videos  = Video::where('canal_id', $canalId)->get();
            foreach ($videos as $video) {
                $video->delete();
            }
            $canal->delete();
            $this->registrarActividadDarDeBajaCanal($canal, $usuario, $motivo);
            $mailController = new MailController();
            $mailController->correoBajaDeCanal($usuario->email, $usuario->name, $canal->nombre, $motivo);
            return redirect()->route(self::ROUTE_LISTAR_CANALES)->with('success', 'Tu canal y todos tus videos se han dado de baja correctamente. Se ha enviado un correo de notificación.');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->withErrors(['message' => 'Lo sentimos, tu canal no pudo ser encontrado']);
        } catch (QueryException $exception) {
            return redirect()->back()->withErrors(['message' => 'Ocurrió un error al dar de baja tu canal y tus videos, por favor inténtalo de nuevo más tarde']);
        }
    }

    private function registrarActividadDarDeBajaCanal(Canal $canal, User $usuario, $motivo)
    {
        $detallesActividad = sprintf(
            "Nombre del canal: %s; ID del canal: %d; ID del usuario: %d; Nombre del usuario: %s; Motivo de baja: %s;",
            $canal->nombre,
            $canal->id,
            $usuario->id,
            $usuario->name,
            $motivo
        );

        event(new ActividadRegistrada('Baja de canal', $detallesActividad));
    }

    public function EditarCanal(Request $request, $id)
    {
        try {
            $canal           = Canal::findOrFail($id);
            $datosValidados  = $this->ValidarDatos($request);
            $cambios         = [];
            $portadaAnterior = $canal->portada;
            $cambios         = array_merge($cambios, $this->actualizarNombre($request, $canal));
            $cambios         = array_merge($cambios, $this->actualizarDescripcion($request, $canal));
            if ($request->hasFile('portada')) {
                $this->GuardarPortada($request, $canal);
                if ($portadaAnterior !== $canal->portada) {
                    $cambios['portada'] = 'cambiada';
                }
            }
            $canal->save();
            $this->registrarActividadActualizarCanal($cambios, $canal->id);
            return redirect()->route(self::ROUTE_UPDATE_CANAL, $canal->id)->with('success', 'Canal actualizado correctamente');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['message' => 'Canal no encontrado']);
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['message' => 'Ocurrió un error al actualizar el canal, por favor inténtalo de nuevo más tarde']);
        }
    }

    private function actualizarNombre(Request $request, Canal $canal)
    {
        $cambios = [];
        if ($request->has('nombre') && $request->input('nombre') != $canal->nombre) {
            $cambios['nombre'] = [
                'anterior' => $canal->nombre,
                'nuevo'    => $request->input('nombre'),
            ];
            $canal->nombre = $request->input('nombre');
        }
        return $cambios;
    }

    private function actualizarDescripcion(Request $request, Canal $canal)
    {
        $cambios = [];
        if ($request->has('descripcion') && $request->input('descripcion') != $canal->descripcion) {
            $cambios['descripcion'] = 'cambiada';
            $canal->descripcion     = $request->input('descripcion');
        }
        return $cambios;
    }

    protected function registrarActividadActualizarCanal(array $cambios, $canalId)
    {
        $detalles = '';
        foreach ($cambios as $campo => $valor) {
            if ($campo === 'descripcion' || $campo === 'portada') {
                $detalles .= ucfirst($campo) . ': ' . $valor . '; ';
            } else {
                $detalles .= ucfirst($campo) . ': ' . ($valor['anterior'] ?? 'N/A') . ' -> ' . ($valor['nuevo'] ?? 'N/A') . '; ';
            }
        }
        event(new ActividadRegistrada('Actualización de canal', $detalles));
    }

}
