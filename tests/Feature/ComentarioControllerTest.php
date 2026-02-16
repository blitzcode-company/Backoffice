<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Comentario;
use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Video;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ComentarioControllerTest extends TestCase
{
    protected $user;
    protected $video;
    protected $comentario;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
        Event::fake();
        $this->user = User::where('name', '!=', 'Invitado')->first();
        if (!$this->user) {
            $this->user = User::create(['name' => 'TestUser', 'email' => 'test@user.com', 'password' => bcrypt('123456')]);
        }
        $canal = Canal::where('user_id', $this->user->id)->first();
        if (!$canal) {
            $canal = Canal::create([
                'user_id' => $this->user->id,
                'nombre' => 'Canal Test',
                'stream_key' => 'key_' . uniqid()
            ]);
        }
        $this->video = Video::create([
            'canal_id' => $canal->id,
            'titulo' => 'Video Test Comentarios ' . uniqid(),
            'descripcion' => 'Descripción test',
            'link' => 'http://video.com/test_' . uniqid() . '.mp4',
            'miniatura' => 'http://img.com/test_' . uniqid() . '.jpg',
            'estado' => 'VIDEO',
            'acceso' => 'publico',
            'duracion' => 120
        ]);
        $this->comentario = Comentario::create([
            'usuario_id' => $this->user->id,
            'video_id' => $this->video->id,
            'mensaje' => 'Comentario Base de Prueba',
            'bloqueado' => false
        ]);
    }

    /** @test */
    public function puede_listar_comentarios_del_video()
    {
        $this->actingAs($this->user);

        $comentarios = Comentario::where('video_id', $this->video->id)->whereNull('respuesta_id')->get();
        $response = $this->get(route('comentarios.listado', ['id' => $this->video->id]));
        $response->assertStatus(200);
        $response->assertViewIs('comentarios.listado');
        $response->assertViewHas('comentarios', function ($viewComentarios) use ($comentarios) {
            foreach ($comentarios as $comentario) {
                if (!$viewComentarios->contains('id', $comentario->id)) {
                    return false;
                }
            }
            return true;
        });
    }

    /** @test */
    public function puede_ver_comentario_y_sus_respuestas()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('comentarios.ver', ['comentario_id' => $this->comentario->id]));
        $response->assertStatus(200);
        $response->assertViewIs('comentarios.ver');
        $response->assertViewHas('comentario', function ($viewComentario) {
            return $viewComentario->id === $this->comentario->id;
        });
    }

    /** @test */
    public function puede_crear_un_comentario()
    {
        $this->actingAs($this->user);

        $data = [
            'usuario_id' => $this->user->id,
            'mensaje' => 'Este es un nuevo comentario',
            'video_id' => $this->video->id,
        ];
        $response = $this->post(route('comentarios.crear'), $data);
        $response->assertRedirect(route('comentarios.listado', ['id' => $this->video->id]));
        $this->assertDatabaseHas('comentarios', [
            'mensaje' => 'Este es un nuevo comentario',
            'usuario_id' => $this->user->id,
            'video_id' => $this->video->id,
        ]);
    }

    /** @test */
    public function puede_responder_un_comentario()
    {
        $this->actingAs($this->user);

        $data = [
            'usuario_id' => $this->user->id,
            'mensaje' => 'Esta es una respuesta al comentario',
            'respuesta_id' => $this->comentario->id,
            'video_id' => $this->video->id,
        ];
        $response = $this->post(route('comentarios.responder'), $data);
        $response->assertRedirect(route('comentarios.ver', ['comentario_id' => $this->comentario->id]));
        $this->assertDatabaseHas('comentarios', [
            'mensaje' => 'Esta es una respuesta al comentario',
            'respuesta_id' => $this->comentario->id,
        ]);
    }

    /** @test */
    public function puede_eliminar_un_comentario()
    {
        $this->actingAs($this->user);

        $response = $this->delete(route('comentarios.eliminar', ['comentario_id' => $this->comentario->id]));
        $response->assertRedirect();
        $this->assertSoftDeleted('comentarios', ['id' => $this->comentario->id]);
    }

    /** @test */
    public function puede_restaurar_un_comentario()
    {
        $this->actingAs($this->user);
        $this->comentario->delete();
        $response = $this->post(route('comentarios.restaurar', ['comentario_id' => $this->comentario->id]));
        $response->assertRedirect();
        $this->assertDatabaseHas('comentarios', ['id' => $this->comentario->id, 'deleted_at' => null]);
    }

    /** @test */
    public function puede_actualizar_un_comentario()
    {
        $this->actingAs($this->user);

        $data = ['mensaje' => 'Comentario actualizado'];
        $response = $this->put(route('comentarios.actualizar', ['comentario_id' => $this->comentario->id]), $data);
        $response->assertRedirect(route('comentarios.ver', ['comentario_id' => $this->comentario->id]));
        $this->assertDatabaseHas('comentarios', [
            'id' => $this->comentario->id,
            'mensaje' => 'Comentario actualizado',
        ]);
    }

    /** @test */
    public function puede_bloquear_un_comentario()
    {
        $this->actingAs($this->user);

        $response = $this->patch(route('comentarios.bloquear', ['comentario_id' => $this->comentario->id]));
        $response->assertRedirect();
        $this->assertDatabaseHas('comentarios', ['id' => $this->comentario->id, 'bloqueado' => true]);
    }

    /** @test */
    public function puede_desbloquear_un_comentario()
    {
        $this->actingAs($this->user);
        $this->comentario->bloqueado = true;
        $this->comentario->save();
        $response = $this->patch(route('comentarios.desbloquear', ['comentario_id' => $this->comentario->id]));
        $response->assertRedirect();
        $this->assertDatabaseHas('comentarios', ['id' => $this->comentario->id, 'bloqueado' => false]);
    }
}
