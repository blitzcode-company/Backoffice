<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CanalController extends Controller
{
    private const ROUTE_LISTAR_CANALES = 'listar.canales';
    private const ROUTE_CREAR_CANAL = 'crear-canal';
    private const ROUTE_UPDATE_CANAL = 'update.canal';
    private const USUARIO_EXCLUIDO = 'Invitado';

    public function ListarCanales()
    {
        $canales = Canal::with(['user:id,name,foto'])
            ->whereHas('user', function ($query) {
                $query->where('name', '!=', self::USUARIO_EXCLUIDO);
            })
            ->withCount('videos')
            ->get();

        return view('canales.listar-canales', compact('canales'));
    }

    public function ListarCanalesPorId($id)
    {
        $canal = Canal::with(['user:id,name,foto'])
            ->where('id', $id)
            ->whereHas('user', function ($query) {
                $query->where('name', '!=', self::USUARIO_EXCLUIDO);
            })
            ->withCount('videos')
            ->firstOrFail();

        return view('canales.canal', compact('canal'));
    }

    public function ListarCanalesPorNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $query = Canal::with('user:id,name,foto')
            ->whereHas('user', function ($query) {
                $query->where('name', '!=', self::USUARIO_EXCLUIDO);
            })
            ->withCount('videos');

        if ($nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        $canales = $query->take(10)->get();

        return view('canales.listar-canales', compact('canales'));
    }

    public function ListarVideosDeCanal($canalId)
    {
        $videos = Video::where('canal_id', $canalId)->get();
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
            ->withCount('videos')
            ->firstOrFail();

        return view('canales.editar-canal', compact('canal'));
    }

    public function CrearCanal(Request $request)
    {
        try {
            $userId = $request->input('userId');
            $usuario = User::findOrFail($userId);
            $canalExistente = Canal::where('user_id', $userId)->first();
            if ($canalExistente) {
                return redirect()->back()->withErrors(['message' => 'El usuario ya tiene un canal']);
            }
            $datosValidados = $this->ValidarDatos($request);
            $canal = $this->CrearNuevoCanal($datosValidados, $userId);
            $this->GuardarCanal($canal);
            $this->GuardarPortada($request, $canal);
            $canal->save();
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
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'portada' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
        ]);
    }

    private function CrearNuevoCanal(array $datosValidados, $userId)
    {
        return new Canal([
            'nombre' => $datosValidados['nombre'],
            'descripcion' => $datosValidados['descripcion'] ?? null,
            'user_id' => $userId,
        ]);
    }

    private function GuardarPortada(Request $request, Canal $canal)
    {
        if ($request->hasFile('portada')) {
            $portada = $request->file('portada');
            $folderPath = 'portadas/' . $canal->id;
            $rutaPortada = $portada->store($folderPath, 's3');
            $urlPortada = str_replace('minio', env('BLITZVIDEO_HOST'), Storage::disk('s3')->url($rutaPortada));
            $canal->portada = $urlPortada;
        }
    }

    private function GuardarCanal(Canal $canal)
    {
        return $canal->save();
    }

    public function DarDeBajaCanal($canalId)
    {
        try {
            $canal = Canal::findOrFail($canalId);
            $videos = Video::where('canal_id', $canalId)->get();
            foreach ($videos as $video) {
                $video->delete();
            }
            $canal->delete();

            return redirect()->route(self::ROUTE_LISTAR_CANALES)->with('success', 'Tu canal y todos tus videos se han dado de baja correctamente');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->withErrors(['message' => 'Lo sentimos, tu canal no pudo ser encontrado']);
        } catch (QueryException $exception) {
            return redirect()->back()->withErrors(['message' => 'Ocurrió un error al dar de baja tu canal y tus videos, por favor inténtalo de nuevo más tarde']);
        }
    }

    public function EditarCanal(Request $request, $id)
    {
        try {
            $canal = Canal::findOrFail($id);

            $datosValidados = $this->ValidarDatos($request);
            $canal->nombre = $datosValidados['nombre'];
            $canal->descripcion = $datosValidados['descripcion'] ?? $canal->descripcion;

            $this->GuardarPortada($request, $canal);

            $canal->save();

            return redirect()->route(self::ROUTE_UPDATE_CANAL, $canal->id)->with('success', 'Canal actualizado correctamente');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['message' => 'Canal no encontrado']);
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['message' => 'Ocurrió un error al actualizar el canal, por favor inténtalo de nuevo más tarde']);
        }
    }
}
