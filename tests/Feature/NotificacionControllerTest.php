<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Notificacion;
use App\Models\Blitzvideo\Video;
use App\Models\Blitzvideo\Comentario;
use App\Http\Controllers\Blitzvideo\NotificacionController;
use Tests\TestCase;

class NotificacionControllerTest extends TestCase
{
    protected $user;
    protected $video;
    protected $comentario;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
        $this->user = User::where('name', '!=', 'Invitado')->first();
        if (!$this->user) {
             $this->user = User::create([
                'name' => 'TestUserNotif',
                'email' => 'testnotif_' . uniqid() . '@user.com',
                'password' => bcrypt('123456')
            ]);
        }
        $canal = Canal::where('user_id', $this->user->id)->first();
        if (!$canal) {
            $canal = Canal::create([
                'user_id' => $this->user->id,
                'nombre' => 'Canal Test Notif',
                'stream_key' => 'key_' . uniqid()
            ]);
        }
        $this->video = Video::create([
            'canal_id' => $canal->id,
            'titulo' => 'Video Test Notif ' . uniqid(),
            'descripcion' => 'Descripción test',
            'link' => 'http://video.com/test_notif_' . uniqid() . '.mp4',
            'miniatura' => 'http://img.com/test_notif_' . uniqid() . '.jpg',
            'estado' => 'VIDEO',
            'acceso' => 'publico',
            'duracion' => 120
        ]);
        $this->comentario = Comentario::create([
            'usuario_id' => $this->user->id,
            'video_id' => $this->video->id,
            'mensaje' => 'Comentario Test Notif',
            'bloqueado' => false
        ]);
    }

    /** @test */
    public function debe_crear_notificacion_de_bloqueo_de_usuario()
    {
        $this->actingAs($this->user);

        $usuario = $this->user;
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
        $this->actingAs($this->user);

        $usuario = $this->user;
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
        $this->actingAs($this->user);

        $video = $this->video;
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
        $this->actingAs($this->user);

        $video = $this->video;
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
        $this->actingAs($this->user);
        $comentario = $this->comentario;
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
