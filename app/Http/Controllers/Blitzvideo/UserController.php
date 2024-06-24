<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function listarTodosLosUsuarios()
    {
        $users = User::with('canal')->get();
        return response()->json($users);
    }

    public function listarUsuarioPorId($id)
    {
        $user = User::with('canal')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    public function listarUsuariosPorNombre($nombre)
    {
        $users = User::with('canal')
                    ->where('name', 'like', '%' . $nombre . '%')
                    ->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No se encontraron usuarios con ese nombre'], 404);
        }

        return response()->json($users);
    }
}
