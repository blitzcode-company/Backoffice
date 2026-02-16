<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\User;
use App\Traits\Paginable;
use Illuminate\Http\Response;
use Tests\TestCase;

class CanalControllerTest extends TestCase
{

    protected $user;
    use Paginable;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function listar_todos_los_canales()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->get(route('canal.listar'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canales');
    }

    /** @test */
    public function mostrar_canal_por_id()
    {
        $user = User::first();
        $this->actingAs($user);

        $canal = Canal::whereHas('user', function ($query) {
            $query->where('name', '!=', 'Invitado');
        })->first();
        $response = $this->get(route('canal.detalle', ['id' => $canal->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canal');
    }

    /** @test */
    public function listar_canales_por_nombre()
    {
        $user = User::first();
        $this->actingAs($user);

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
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('canal.crear.formulario'));
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function mostrar_formulario_editar_canal()
    {
        $user = User::first();
        $this->actingAs($user);

        $canal = Canal::whereHas('user', function ($query) {
            $query->where('name', '!=', 'Invitado');
        })->first();
        $response = $this->get(route('canal.editar.formulario', ['id' => $canal->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('canal');
    }

    /** @test */
    public function actualizar_canal()
    {
        $user = User::first();
        $this->actingAs($user);

        $canal = Canal::latest()->first();
        $this->assertNotNull($canal, 'No hay canales en la base de datos.');
        $response = $this->put(route('canal.editar', ['id' => $canal->id]), [
            'nombre' => 'Canal Actualizado',
            'descripcion' => 'Descripción actualizada',
            'portada' => null,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('canals', [
            'id' => $canal->id,
            'nombre' => 'Canal Actualizado',
            'descripcion' => 'Descripción actualizada',
        ]);

    }

/** @test */
    public function crear_canal()
    {
        $user = User::first();
        $this->actingAs($user);

        $email = 'usuario' . uniqid() . '.prueba@gmail.com';
        $this->user = User::create([
            'name' => 'prueba',
            'email' => $email,
            'password' => bcrypt('contraseña123'),
        ]);

        $response = $this->post(route('canal.crear'), [
            'userId' => $this->user->id,
            'nombre' => 'Nuevo Canal',
            'descripcion' => 'Descripción del nuevo canal',
            'portada' => null,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('canals', [
            'nombre' => 'Nuevo Canal',
            'user_id' => $this->user->id,
        ]);
        $canal = Canal::where('nombre', 'Nuevo Canal')->first();
        $this->assertNotNull($canal->stream_key);
    }

/** @test */
    public function test_dar_de_baja_canal_no_encontrado()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->delete(route('canal.eliminar', ['id' => 999]), [
            'motivo' => 'Prueba',
        ]);
        $response->assertSessionHasErrors(['message' => 'Lo sentimos, tu canal no pudo ser encontrado']);
    }

    /** @test */
    public function eliminar_canal()
    {
        $user = User::first();
        $this->actingAs($user);

        $canal = Canal::latest()->first();
        $response = $this->delete(route('canal.eliminar', ['id' => $canal->id]), [
            'motivo' => 'Eliminación de prueba',
        ]);
        $this->assertSoftDeleted('canals', ['id' => $canal->id]);
    }
}
