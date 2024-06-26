<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function listarTodosLosUsuarios()
    {
        $users = User::with('canal')
            ->where('name', '!=', 'Invitado')
            ->take(10)
            ->get();
        return view('usuario.usuarios', compact('users'));
    }

    public function mostrarUsuarioPorId($id)
    {
        $user = User::with('canal')->find($id);

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        return view('usuario.usuario', compact('user'));
    }

    public function listarUsuariosPorNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $query = User::with('canal')->where('name', '!=', 'Invitado');
        if ($nombre) {
            $query->where('name', 'like', '%' . $nombre . '%');
        }
        $users = $query->take(10)->get();
        return view('usuario.usuarios', compact('users'));
    }
}
