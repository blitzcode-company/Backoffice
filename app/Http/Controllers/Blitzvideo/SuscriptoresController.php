<?php
namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Suscribe;
use App\Traits\Paginable;
use Illuminate\Http\Request;

class SuscriptoresController extends Controller
{
    use Paginable;

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

    public function listarSuscriptores($id, Request $request)
    {
        $canal = Canal::findOrFail($id);

        $suscriptores = $canal->suscriptores()
            ->select('users.id', 'users.name', 'users.email', 'users.foto', 'suscribe.id as suscribe_id')
            ->whereNull('suscribe.deleted_at')
            ->orderBy('users.id', 'desc')
            ->paginate(6);

        $host   = $this->obtenerHostMinio();
        $bucket = $this->obtenerBucket();

        foreach ($suscriptores as $suscriptor) {
            if ($suscriptor->foto) {
                $suscriptor->foto = $this->obtenerUrlArchivo($suscriptor->foto, $host, $bucket);
            }
        }

        return view('canales.listar-suscriptores', compact('canal', 'suscriptores'));
    }

    public function listarSuscriptoresPorNombre($canalId, Request $request)
    {
        $nombre = $request->query('nombre');
        $canal  = Canal::findOrFail($canalId);

        $suscriptoresQuery = $canal->suscriptores()
            ->select('users.id', 'users.name', 'users.email', 'users.foto', 'suscribe.id as suscribe_id')
            ->whereNull('suscribe.deleted_at')
            ->orderBy('users.id', 'desc');

        if ($nombre) {
            $suscriptoresQuery->where('users.name', 'like', '%' . $nombre . '%');
        }
        $suscriptores = $suscriptoresQuery->paginate(12, ['*'], 'page', $request->input('page', 1));
        $host         = $this->obtenerHostMinio();
        $bucket       = $this->obtenerBucket();

        foreach ($suscriptores as $suscriptor) {
            if ($suscriptor->foto) {
                $suscriptor->foto = $this->obtenerUrlArchivo($suscriptor->foto, $host, $bucket);
            }
        }
        return view('canales.listar-suscriptores', compact('canal', 'suscriptores'));
    }

    public function desuscribir($canalId, $suscribeId)
    {
        $suscripcion = Suscribe::where('canal_id', $canalId)->where('id', $suscribeId)->firstOrFail();
        $suscripcion->delete();
        return redirect()->route('suscriptores.listar', ['id' => $canalId])
            ->with('success', 'Usuario desuscrito con éxito.');
    }

    public function suscribir($canalId, Request $request)
    {
        $canal = Canal::findOrFail($canalId);
        $request->validate([
            'usuario_id' => 'required|exists:App\Models\Blitzvideo\User,id',
        ]);
        $usuarioId = $request->input('usuario_id');

        $suscripcion = Suscribe::withTrashed()
            ->where('canal_id', $canalId)
            ->where('user_id', $usuarioId)
            ->first();

        if ($suscripcion) {
            if ($suscripcion->trashed()) {
                $suscripcion->restore();
                return redirect()->route('suscriptores.listar', ['id' => $canalId])
                    ->with('success', 'Usuario suscrito con éxito.');
            }
            return redirect()->route('canal.detalle', ['id' => $canalId])
                ->with('warning', 'El usuario ya está suscrito a este canal.');
        }
        $canal->suscriptores()->attach($usuarioId);
        return redirect()->route('suscriptores.listar', ['id' => $canalId])
            ->with('success', 'Usuario suscrito con éxito.');
    }

}
