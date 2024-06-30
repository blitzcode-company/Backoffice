<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function MostrarFormularioCrearUsuario()
    {

        return view('usuario.crear-usuario');
    }

    public function MostrarFormularioActualizarUsuario($id)
    {
        $user = User::with('canal')->find($id);
        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }
        return view('usuario.editar-usuario', compact('user'));
    }

    public function CrearUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $usuario = new User();
            $usuario->name = $request->input('name');
            $usuario->email = $request->input('email');
            $usuario->password = bcrypt($request->input('password'));
            $usuario->save();
            $folderPath = 'perfil/' . $usuario->id;

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $rutaFoto = $foto->store($folderPath, 's3');
                $urlFoto = str_replace('minio', 'localhost', Storage::disk('s3')->url($rutaFoto));
                $usuario->foto = $urlFoto;
                $usuario->save();
            }

            return redirect()->route('crear.usuario')->with('success', 'Usuario creado correctamente');
        } catch (\Exception $exception) {
            return back()->withInput()->withErrors(['error' => 'Error al crear el usuario']);
        }
    }

    public function ListarTodosLosUsuarios()
    {
        $users = User::with('canal')
            ->where('name', '!=', 'Invitado')
            ->take(10)
            ->get();
        return view('usuario.usuarios', compact('users'));
    }

    public function MostrarUsuarioPorId($id)
    {
        $user = User::with('canal')->find($id);

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        return view('usuario.usuario', compact('user'));
    }

    public function ListarUsuariosPorNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $query = User::with('canal')->where('name', '!=', 'Invitado');
        if ($nombre) {
            $query->where('name', 'like', '%' . $nombre . '%');
        }
        $users = $query->take(10)->get();
        return view('usuario.usuarios', compact('users'));
    }

    public function EliminarUsuario($id)
    {
        try {
            $usuario = User::find($id);

            if (!$usuario) {
                abort(404, 'Usuario no encontrado');
            }
            if ($usuario->foto) {
                $folderName = 'perfil/' . $usuario->id;
                Storage::disk('s3')->delete($usuario->foto);
                if (Storage::disk('s3')->exists($folderName)) {
                    Storage::disk('s3')->deleteDirectory($folderName);
                }
            }
            $usuario->delete();

            return redirect()->route('usuarios')->with('success', 'Usuario eliminado correctamente');
        } catch (\Exception $exception) {
            return back()->withErrors(['error' => 'Error al eliminar el usuario']);
        }
    }

    public function ActualizarUsuario(Request $request, $id)
    {
        $usuario = User::find($id);

        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if ($request->has('name') && $request->input('name') != $usuario->name) {
                $usuario->name = $request->input('name');
            }

            if ($request->has('email') && $request->input('email') != $usuario->email) {
                $usuario->email = $request->input('email');
            }

            if ($request->filled('password')) {
                $usuario->password = bcrypt($request->input('password'));
            }

            if ($request->hasFile('foto')) {
                if ($usuario->foto) {
                    $folderName = 'perfil/' . $usuario->id;
                    Storage::disk('s3')->delete($usuario->foto);
                    if (Storage::disk('s3')->exists($folderName)) {
                        Storage::disk('s3')->deleteDirectory($folderName);
                    }
                }

                $folderPath = 'perfil/' . $usuario->id;
                $foto = $request->file('foto');
                $rutaFoto = $foto->store($folderPath, 's3');
                $urlFoto = str_replace('minio', 'localhost', Storage::disk('s3')->url($rutaFoto));
                $usuario->foto = $urlFoto;
            }

            $usuario->save();

            return redirect()->route('update.usuario', ['id' => $usuario->id])->with('success', 'Usuario actualizado correctamente');
        } catch (\Exception $exception) {
            return back()->withInput()->withErrors(['error' => 'Error al actualizar el usuario']);
        }
    }

}
