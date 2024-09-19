<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Comentario;
use App\Models\Blitzvideo\Video;
use Tests\TestCase;

class ComentarioControllerTest extends TestCase
{
    // use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function puede_listar_comentarios_del_video()
    {
        $video = Video::find(3);
        $comentarios = Comentario::where('video_id', $video->id)->whereNull('respuesta_id')->get();
        $response = $this->get(route('comentarios.listado', ['id' => $video->id]));
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
        $comentario = Comentario::where('video_id', 3)->first();
        $response = $this->get(route('comentarios.ver', ['comentario_id' => $comentario->id]));
        $response->assertStatus(200);
        $response->assertViewIs('comentarios.ver');
        $response->assertViewHas('comentario', function ($viewComentario) use ($comentario) {
            return $viewComentario->id === $comentario->id;
        });
    }

    /** @test */
    public function puede_crear_un_comentario()
    {
        $data = [
            'usuario_id' => 2,
            'mensaje' => 'Este es un nuevo comentario',
            'video_id' => 3,
        ];
        $response = $this->post(route('comentarios.crear'), $data);
        $response->assertRedirect(route('comentarios.listado', ['id' => 3]));
        $this->assertDatabaseHas('comentarios', [
            'mensaje' => 'Este es un nuevo comentario',
            'usuario_id' => 2,
            'video_id' => 3,
        ]);
    }

    /** @test */
    public function puede_responder_un_comentario()
    {
        $video_id = 3;
        $comentario = Comentario::where('video_id', $video_id)->first();
        $data = [
            'usuario_id' => 2,
            'mensaje' => 'Esta es una respuesta al comentario',
            'respuesta_id' => $comentario->id,
            'video_id' => $video_id,
        ];
        $response = $this->post(route('comentarios.responder'), $data);
        $response->assertRedirect(route('comentarios.ver', ['comentario_id' => $comentario->id]));
        $this->assertDatabaseHas('comentarios', [
            'mensaje' => 'Esta es una respuesta al comentario',
            'respuesta_id' => $comentario->id,
        ]);
    }

    /** @test */
    public function puede_eliminar_un_comentario()
    {
        $comentario = Comentario::where('video_id', 3)->first();
        $response = $this->delete(route('comentarios.eliminar', ['comentario_id' => $comentario->id]));
        $response->assertRedirect();
        $this->assertSoftDeleted('comentarios', ['id' => $comentario->id]);
    }

    /** @test */
    public function puede_restaurar_un_comentario()
    {
        $comentario = Comentario::onlyTrashed()->where('video_id', 3)->first();
        $response = $this->post(route('comentarios.restaurar', ['comentario_id' => $comentario->id]));
        $response->assertRedirect();
        $this->assertDatabaseHas('comentarios', ['id' => $comentario->id, 'deleted_at' => null]);
    }

    /** @test */
    public function puede_actualizar_un_comentario()
    {
        $comentario = Comentario::where('video_id', 3)->first();
        $data = ['mensaje' => 'Comentario actualizado'];
        $response = $this->put(route('comentarios.actualizar', ['comentario_id' => $comentario->id]), $data);
        $response->assertRedirect(route('comentarios.ver', ['comentario_id' => $comentario->id]));
        $this->assertDatabaseHas('comentarios', [
            'id' => $comentario->id,
            'mensaje' => 'Comentario actualizado',
        ]);
    }

    /** @test */
    public function puede_bloquear_un_comentario()
    {
        $comentario = Comentario::where('video_id', 3)->first();
        $response = $this->patch(route('comentarios.bloquear', ['comentario_id' => $comentario->id]));
        $response->assertRedirect();
        $this->assertDatabaseHas('comentarios', ['id' => $comentario->id, 'bloqueado' => true]);
    }

    /** @test */
    public function puede_desbloquear_un_comentario()
    {
        $comentario = Comentario::where('video_id', 3)->where('bloqueado', true)->first();
        $response = $this->patch(route('comentarios.desbloquear', ['comentario_id' => $comentario->id]));
        $response->assertRedirect();
        $this->assertDatabaseHas('comentarios', ['id' => $comentario->id, 'bloqueado' => false]);
    }
}
