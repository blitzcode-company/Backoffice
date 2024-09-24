<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Paginable;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use Paginable;

    public function listarUsuarios(Request $request)
    {
        $query = User::query();
        $usuarios = $this->paginateBuilder($query, 9, $request->input('page', 1));
        return view('admin.usuarios', compact('usuarios'));
    }
}
