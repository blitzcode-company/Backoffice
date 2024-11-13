<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Notificacion;
use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Video;

class NotificacionController extends Controller
{

    public function crearNotificacionDeBloqueoDeUsuario(int $usuarioId, string $motivo)
    {
        $usuario = User::findOrFail($usuarioId);
        $mensaje = "Su cuenta ha sido bloqueada por " . $motivo;
        $notificacion = Notificacion::create([
            'mensaje' => $mensaje,
            'referencia_id' => $usuarioId,
            'referencia_tipo' => 'blocked_user',
        ]);
        $usuario->notificaciones()->attach($notificacion->id, ['leido' => false]);
        return $notificacion;
    }

    public function crearNotificacionDeDesbloqueoDeUsuario(int $usuarioId)
    {
        $usuario = User::findOrFail($usuarioId);
        $mensaje = "Su cuenta ha sido desbloqueada y ahora tiene acceso nuevamente.";
        $notificacion = Notificacion::create([
            'mensaje' => $mensaje,
            'referencia_id' => $usuarioId,
            'referencia_tipo' => 'unblocked_user',
        ]);
        $usuario->notificaciones()->attach($notificacion->id, ['leido' => false]);
        return $notificacion;
    }

    public function crearNotificacionDeBloqueoDeVideo($video, string $motivo)
    {
        $canal = $video->canal;
        $usuario = User::findOrFail($canal->user_id);
        $mensaje = "Su video con ID " . $video->id . " ha sido bloqueado por " . $motivo;
        $notificacion = Notificacion::create([
            'mensaje' => $mensaje,
            'referencia_id' => $video->id,
            'referencia_tipo' => 'blocked_video',
        ]);
        $usuario->notificaciones()->attach($notificacion->id, ['leido' => false]);
        return $notificacion;
    }

    public function crearNotificacionDeDesbloqueoDeVideo($video)
    {
        $canal = $video->canal;
        $usuario = User::findOrFail($canal->user_id);
        $mensaje = "Su video con ID " . $video->id . " ha sido desbloqueado y ahora está disponible nuevamente.";

        $notificacion = Notificacion::create([
            'mensaje' => $mensaje,
            'referencia_id' => $video->id,
            'referencia_tipo' => 'unblocked_video',
        ]);

        $usuario->notificaciones()->attach($notificacion->id, ['leido' => false]);

        return $notificacion;
    }

    public function crearNotificacionDeBloqueoDeComentario($comentario)
    {
        $video = Video::findOrFail($comentario->video_id);
        $usuario = User::findOrFail($comentario->usuario_id);
        $mensaje = 'Su comentario en el video "' . $video->titulo . '" ha sido bloqueado.';
        $notificacion = Notificacion::create([
            'mensaje' => $mensaje,
            'referencia_id' => $comentario->video_id,
            'referencia_tipo' => 'blocked_comment',
        ]);
        $usuario->notificaciones()->attach($notificacion->id, ['leido' => false]);
        return $notificacion;
    }
}
