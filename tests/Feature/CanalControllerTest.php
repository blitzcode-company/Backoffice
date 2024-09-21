<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CanalControllerTest extends TestCase
{
    //use WithoutMiddleware;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function listar_todos_los_canales()
    {
        $response = $this->get(route('canal.listar'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canales');
    }

    /** @test */
    public function mostrar_canal_por_id()
    {
        $response = $this->get(route('canal.detalle', ['id' => 3]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canal');
    }

    /** @test */
    public function listar_canales_por_nombre()
    {
        $response = $this->get(route('canal.nombre', ['nombre' => 'Canal']));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canales');
        $canales = $response->viewData('canales');
        $this->assertNotEmpty($canales);
        $this->assertTrue($canales->contains(function ($canal) {
            return stripos($canal->nombre, 'Canal') !== false;
        }));
    }

    /** @test */
    public function mostrar_formulario_crear_canal()
    {
        $response = $this->get(route('canal.crear.formulario'));
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function mostrar_formulario_editar_canal()
    {
        $response = $this->get(route('canal.editar.formulario', ['id' => 3]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canal');
    }

    /** @test */
    public function actualizar_canal()
    {
        $canal = Canal::latest()->first();
        $this->assertNotNull($canal, 'No hay canales en la base de datos.');

        $response = $this->put(route('canal.editar', ['id' => $canal->id]), [
            'nombre' => 'Canal Actualizado',
            'descripcion' => 'Descripción actualizada',
            'portada' => null,
        ]);
        $response->assertSessionHas('success');
    }

/** @test */
    public function crear_canal()
    {
        $this->user = User::create([
            'name' => 'prueba',
            'email' => 'usuario2.prueba@gmail.com',
            'password' => bcrypt('contraseña123'),
        ]);

        $response = $this->post(route('canal.crear'), [
            'userId' => $this->user->id,
            'nombre' => 'Nuevo Canal',
            'descripcion' => 'Descripción del nuevo canal',
            'portada' => null,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('canal.crear.formulario'));
        $this->assertDatabaseHas('canals', [
            'nombre' => 'Nuevo Canal',
            'user_id' => $this->user->id,
        ]);
    }

/** @test */
    public function test_dar_de_baja_canal_no_encontrado()
    {
        $response = $this->delete(route('canal.eliminar', ['id' => 999]), [
            'motivo' => 'Prueba',
        ]);
        $response->assertSessionHasErrors(['message' => 'Lo sentimos, tu canal no pudo ser encontrado']);
    }

    /** @test */
    public function eliminar_canal()
    {
        $response = $this->delete(route('canal.eliminar', ['id' => 6]));
        $this->assertSoftDeleted('canals', ['id' => 6]);
    }
}
