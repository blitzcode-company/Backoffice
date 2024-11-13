<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Notificacion;
use App\Models\Blitzvideo\Video;
use App\Models\Blitzvideo\Comentario;
use App\Http\Controllers\Blitzvideo\NotificacionController;
use Tests\TestCase;

class NotificacionControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function debe_crear_notificacion_de_bloqueo_de_usuario()
    {
        $user = User::first();
        $this->actingAs($user);

        $usuario = User::find(2);
        $motivo = "violación de términos";
        $controller = new NotificacionController();
        $notificacion = $controller->crearNotificacionDeBloqueoDeUsuario($usuario->id, $motivo);
        $this->assertInstanceOf(Notificacion::class, $notificacion);
        $this->assertEquals('blocked_user', $notificacion->referencia_tipo);
        $this->assertEquals("Su cuenta ha sido bloqueada por $motivo", $notificacion->mensaje);
        $this->assertDatabaseHas('notificacion', [
            'referencia_id' => $usuario->id,
            'referencia_tipo' => 'blocked_user',
            'mensaje' => "Su cuenta ha sido bloqueada por $motivo",
        ]);
        $this->assertTrue($usuario->notificaciones->count() > 0);
    }

    /** @test */
    public function debe_crear_notificacion_de_desbloqueo_de_usuario()
    {

        $user = User::first();
        $this->actingAs($user);

        $usuario = User::find(2);
        $controller = new NotificacionController();
        $notificacion = $controller->crearNotificacionDeDesbloqueoDeUsuario($usuario->id);
        $this->assertInstanceOf(Notificacion::class, $notificacion);
        $this->assertEquals('unblocked_user', $notificacion->referencia_tipo);
        $this->assertEquals('Su cuenta ha sido desbloqueada y ahora tiene acceso nuevamente.', $notificacion->mensaje);
        $this->assertDatabaseHas('notificacion', [
            'referencia_id' => $usuario->id,
            'referencia_tipo' => 'unblocked_user',
            'mensaje' => 'Su cuenta ha sido desbloqueada y ahora tiene acceso nuevamente.',
        ]);
        $this->assertTrue($usuario->notificaciones->count() > 0);
    }

    /** @test */
    public function debe_crear_notificacion_de_bloqueo_de_video()
    {
        $user = User::first();
        $this->actingAs($user);

        $video = Video::first();
        $motivo = "contenido inapropiado";
        $controller = new NotificacionController();
        $notificacion = $controller->crearNotificacionDeBloqueoDeVideo($video, $motivo);
        $this->assertInstanceOf(Notificacion::class, $notificacion);
        $this->assertEquals('blocked_video', $notificacion->referencia_tipo);
        $this->assertEquals("Su video con ID {$video->id} ha sido bloqueado por $motivo", $notificacion->mensaje);
        $this->assertDatabaseHas('notificacion', [
            'referencia_id' => $video->id,
            'referencia_tipo' => 'blocked_video',
            'mensaje' => "Su video con ID {$video->id} ha sido bloqueado por $motivo",
        ]);
        $this->assertTrue($video->canal->user->notificaciones->count() > 0);
    }

    /** @test */
    public function debe_crear_notificacion_de_desbloqueo_de_video()
    {
        $user = User::first();
        $this->actingAs($user);

        $video = Video::first();
        $controller = new NotificacionController();
        $notificacion = $controller->crearNotificacionDeDesbloqueoDeVideo($video);
        $this->assertInstanceOf(Notificacion::class, $notificacion);
        $this->assertEquals('unblocked_video', $notificacion->referencia_tipo);
        $this->assertEquals("Su video con ID {$video->id} ha sido desbloqueado y ahora está disponible nuevamente.", $notificacion->mensaje);
        $this->assertDatabaseHas('notificacion', [
            'referencia_id' => $video->id,
            'referencia_tipo' => 'unblocked_video',
            'mensaje' => "Su video con ID {$video->id} ha sido desbloqueado y ahora está disponible nuevamente.",
        ]);
        $this->assertTrue($video->canal->user->notificaciones->count() > 0);
    }

    /** @test */
    public function debe_crear_notificacion_de_bloqueo_de_comentario()
    {
        $user = User::first();
        $this->actingAs($user);
        $comentario = Comentario::first();
        $controller = new NotificacionController();
        $notificacion = $controller->crearNotificacionDeBloqueoDeComentario($comentario);
        $this->assertInstanceOf(Notificacion::class, $notificacion);
        $this->assertEquals('blocked_comment', $notificacion->referencia_tipo);
        $this->assertEquals("Su comentario en el video \"{$comentario->video->titulo}\" ha sido bloqueado.", $notificacion->mensaje);
        $this->assertDatabaseHas('notificacion', [
            'referencia_id' => $comentario->video_id,
            'referencia_tipo' => 'blocked_comment',
            'mensaje' => "Su comentario en el video \"{$comentario->video->titulo}\" ha sido bloqueado.",
        ]);
    }
}
