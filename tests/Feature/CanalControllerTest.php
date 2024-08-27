<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class CanalControllerTest extends TestCase
{
    // use WithoutMiddleware;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function listar_todos_los_canales()
    {
        $response = $this->get(route('listar.canales'));
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
        $response = $this->post(route('canales-nombre'), ['nombre' => 'Canal']);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canales');
    }

    /** @test */
    public function mostrar_formulario_crear_canal()
    {
        $response = $this->get(route('crear-canal'));
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function mostrar_formulario_editar_canal()
    {
        $response = $this->get(route('update.canal', ['id' => 3]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canal');
    }

    /** @test */
    public function actualizar_canal()
    {
        $canal = Canal::latest()->first();
        $this->assertNotNull($canal, 'No hay canales en la base de datos.');

        $response = $this->put(route('update.canal', ['id' => $canal->id]), [
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

        $response = $this->post(route('canales.store'), [
            'userId' => $this->user->id,
            'nombre' => 'Nuevo Canal',
            'descripcion' => 'Descripción del nuevo canal',
            'portada' => null,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('crear-canal'));
        $this->assertDatabaseHas('canals', [
            'nombre' => 'Nuevo Canal',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function eliminar_canal()
    {
        $response = $this->delete(route('eliminar.canal', ['id' => 12]));
        $response->assertRedirect(route('listar.canales'));
        $this->assertSoftDeleted('canals', ['id' => 12]);
    }
}
