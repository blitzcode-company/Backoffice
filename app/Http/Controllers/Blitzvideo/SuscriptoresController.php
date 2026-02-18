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

    public function listarSuscriptores($id, Request $request)
    {
        $canal = Canal::findOrFail($id);
        $suscriptoresQuery = $canal->suscriptores()
            ->select('users.id', 'users.name', 'users.email', 'users.foto', 'suscribe.id as suscribe_id')
            ->whereNull('suscribe.deleted_at')
            ->orderBy('users.id', 'desc');

        $suscriptores = $suscriptoresQuery->paginate(6, ['*'], 'page', $request->input('page', 1));
        return view('canales.listar-suscriptores', compact('canal', 'suscriptores'));
    }

    public function listarSuscriptoresPorNombre($canalId, Request $request)
    {
        $nombre = $request->query('nombre');
        $canal = Canal::findOrFail($canalId);
        $suscriptoresQuery = $canal->suscriptores()
            ->select('users.id', 'users.name', 'users.email', 'users.foto', 'suscribe.id as suscribe_id')
            ->whereNull('suscribe.deleted_at')
            ->orderBy('users.id', 'desc');

        if ($nombre) {
            $suscriptoresQuery->where('users.name', 'like', '%' . $nombre . '%');
        }

        $suscriptores = $suscriptoresQuery->paginate(6, ['*'], 'page', $request->input('page', 1));
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
