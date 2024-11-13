<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Events\ActividadRegistrada;
use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\User;
use App\Traits\Paginable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private const ROUTE_CREAR_USUARIO = 'usuario.crear.formulario';
    private const ROUTE_LISTAR_USUARIOS = 'usuario.listar';
    private const ROUTE_EDITAR_USUARIO = 'usuario.editar.formulario';

    use Paginable;

    public function MostrarFormularioCrearUsuario()
    {
        return view('usuario.crear-usuario');
    }

    public function MostrarFormularioActualizarUsuario($id)
    {
        $user = User::with('canales')->find($id);
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
            'foto' => 'image|mimes:jpeg,png,jpg,gif,avif|max:2048',
            'fecha_de_nacimiento' => 'required|date',
        ]);

        try {
            $usuario = $this->crearNuevoUsuario($request);

            if ($usuario) {
                $this->subirFoto($request, $usuario);
                $this->registrarActividadCrearUsuario($usuario);
                return redirect()->route(self::ROUTE_CREAR_USUARIO)->with('success', 'Usuario creado correctamente');
            } else {
                return back()->withInput()->withErrors(['error' => 'No se pudo guardar el usuario']);
            }
        } catch (\Exception $exception) {
            return back()->withInput()->withErrors(['error' => 'Error al crear el usuario']);
        }
    }

    private function crearNuevoUsuario(Request $request)
    {
        $usuario = new User();
        $usuario->name = $request->input('name');
        $usuario->email = $request->input('email');
        $usuario->password = bcrypt($request->input('password'));
        $usuario->fecha_de_nacimiento = $request->input('fecha_de_nacimiento');
        $usuario->premium = $request->has('premium');
        $usuario->save();
        return $usuario;
    }

    private function subirFoto(Request $request, User $usuario)
    {
        if ($request->hasFile('foto')) {
            $folderPath = 'perfil/' . $usuario->id;
            $foto = $request->file('foto');
            $rutaFoto = $foto->store($folderPath, 's3');
            $urlFoto = str_replace('minio', env('BLITZVIDEO_HOST'), Storage::disk('s3')->url($rutaFoto));
            $usuario->foto = $urlFoto;
            $usuario->save();
        }
    }

    private function registrarActividadCrearUsuario(User $usuario)
    {
        $detallesActividad = sprintf(
            "Nombre: %s; id: %d; Email: %s; Premium: %s;",
            $usuario->name,
            $usuario->id,
            $usuario->email,
            $usuario->premium ? 'Sí' : 'No'
        );

        event(new ActividadRegistrada('Creación de usuario', $detallesActividad));
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
            event(new ActividadRegistrada('Eliminación de usuario', 'Se eliminó el usuario con ID: ' . $usuario->id));
            return redirect()->route(self::ROUTE_LISTAR_USUARIOS)->with('success', 'Usuario eliminado correctamente');
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

        $request->validate($this->validarDatos($id));

        $cambios = [];

        try {
            $cambios = array_merge($cambios, $this->actualizarNombre($request, $usuario));
            $cambios = array_merge($cambios, $this->actualizarEmail($request, $usuario));
            $cambios = array_merge($cambios, $this->actualizarPassword($request, $usuario));
            $cambios = array_merge($cambios, $this->actualizarFoto($request, $usuario));
            $cambios = array_merge($cambios, $this->actualizarFechaDeNacimiento($request, $usuario));
            $cambios = array_merge($cambios, $this->actualizarPremium($request, $usuario));

            $usuario->save();

            $this->registrarActividadActualizarUsuario($cambios, $usuario->id);

            return redirect()->route(self::ROUTE_EDITAR_USUARIO, ['id' => $usuario->id])->with('success', 'Usuario actualizado correctamente');
        } catch (\Exception $exception) {
            return back()->withInput()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    private function validarDatos($id)
    {
        return [
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
            'fecha_de_nacimiento' => 'nullable|date',
            'premium' => 'nullable|boolean',
        ];
    }

    private function actualizarNombre(Request $request, User $usuario)
    {
        $cambios = [];

        if ($request->has('name') && $request->input('name') != $usuario->name) {
            $cambios['Nombre'] = [
                'anterior' => $usuario->name,
                'nuevo' => $request->input('name'),
            ];
            $usuario->name = $request->input('name');
        }

        return $cambios;
    }

    private function actualizarEmail(Request $request, User $usuario)
    {
        $cambios = [];

        if ($request->has('email') && $request->input('email') != $usuario->email) {
            $cambios['Email'] = [
                'anterior' => $usuario->email,
                'nuevo' => $request->input('email'),
            ];
            $usuario->email = $request->input('email');
        }

        return $cambios;
    }

    private function actualizarPassword(Request $request, User $usuario)
    {
        $cambios = [];

        if ($request->filled('password')) {
            $cambios['Password'] = 'cambiado';
            $usuario->password = bcrypt($request->input('password'));
        }

        return $cambios;
    }

    private function actualizarFechaDeNacimiento(Request $request, User $usuario)
    {
        $cambios = [];
        if ($request->has('fecha_de_nacimiento') && $request->input('fecha_de_nacimiento') != $usuario->fecha_de_nacimiento) {
            $cambios['Fecha de Nacimiento'] = [
                'anterior' => $usuario->fecha_de_nacimiento,
                'nuevo' => $request->input('fecha_de_nacimiento'),
            ];
            $usuario->fecha_de_nacimiento = $request->input('fecha_de_nacimiento');
        }

        return $cambios;
    }

    private function actualizarPremium(Request $request, User $usuario)
    {
        $cambios = [];

        if ($request->has('premium') && $request->input('premium') != $usuario->premium) {
            $cambios['Premium'] = [
                'anterior' => $usuario->premium ? 'Sí' : 'No',
                'nuevo' => $request->input('premium') ? 'Sí' : 'No',
            ];
            $usuario->premium = $request->input('premium');
        }

        return $cambios;
    }

    private function actualizarFoto(Request $request, User $usuario)
    {
        $cambios = [];

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
            $urlFoto = str_replace('minio', env('BLITZVIDEO_HOST'), Storage::disk('s3')->url($rutaFoto));
            $cambios['Foto'] = [
                'anterior' => $usuario->foto,
                'nuevo' => $urlFoto,
            ];
            $usuario->foto = $urlFoto;
        }

        return $cambios;
    }

    private function registrarActividadActualizarUsuario(array $cambios, $usuarioId)
    {
        $detalles = '';
        foreach ($cambios as $campo => $valor) {
            $detalles .= ucfirst($campo) . ': ' . ($valor['anterior'] ?? 'N/A') . ' -> ' . ($valor['nuevo'] ?? 'N/A') . '; ';
        }
        event(new ActividadRegistrada('Actualización de usuario', $detalles));
    }

    public function ListarTodosLosUsuarios(Request $request)
    {
        $usersQuery = User::with('canales')
            ->where('name', '!=', 'Invitado')
            ->orderBy('id', 'desc');
        $users = $this->paginateBuilder($usersQuery, 6, $request->input('page', 1));
        return view('usuario.usuarios', compact('users'));
    }

    public function MostrarUsuarioPorId($id)
    {
        $user = User::with('canales')->find($id);
        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        return view('usuario.usuario', compact('user'));
    }

    public function ListarUsuariosPorNombre(Request $request)
    {
        $nombre = $request->query('nombre');
        $query = User::with('canales')->where('name', '!=', 'Invitado');

        if ($nombre) {
            $query->where('name', 'like', '%' . $nombre . '%');
        }
        $users = $this->paginateBuilder($query, 6, $request->input('page', 1));
        return view('usuario.usuarios', compact('users'));
    }

    public function bloquearUsuario($id, Request $request)
    {
        try {
            $motivo = $request->input('motivo');
            $usuario = User::find($id);

            if (!$usuario) {
                abort(404, 'Usuario no encontrado');
            }
            $usuario->bloqueado = true;
            $usuario->save();
            event(new ActividadRegistrada('Bloqueo de usuario', 'Se bloqueó el usuario con ID: ' . $usuario->id));
            $mailController = new MailController();
            $mailController->correoBloqueoDeUsuario($usuario->email, $usuario->name, $motivo);
            $this->notificacionBloqueoUsuario($motivo, $usuario);
            return redirect()->route(self::ROUTE_LISTAR_USUARIOS)->with('success', 'Usuario bloqueado correctamente');
        } catch (\Exception $exception) {
            return back()->withErrors(['error' => 'Error al bloquear el usuario']);
        }

    }

    private function notificacionBloqueoUsuario($motivo, $usuario)
    {
        $notificacionController = new NotificacionController();
        $notificacionController->crearNotificacionDeBloqueoDeUsuario($usuario->id, $motivo);
    }

    public function desbloquearUsuario($id)
    {
        try {
            $usuario = User::find($id);
            if (!$usuario) {
                abort(404, 'Usuario no encontrado');
            }
            $usuario->bloqueado = false;
            $usuario->save();
            event(new ActividadRegistrada('Desbloqueo de usuario', 'Se desbloqueó el usuario con ID: ' . $usuario->id));
            $this->notificacionDesbloqueoUsuario($usuario);
            return redirect()->route(self::ROUTE_LISTAR_USUARIOS)->with('success', 'Usuario desbloqueado correctamente');
        } catch (\Exception $exception) {
            return back()->withErrors(['error' => 'Error al desbloquear el usuario']);
        }
    }

    private function notificacionDesbloqueoUsuario($usuario)
    {
        $notificacionController = new NotificacionController();
        $notificacionController->crearNotificacionDeDesbloqueoDeUsuario($usuario->id);
    }
}
