<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
   // use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
        Storage::fake('s3');
    }

    /** @test */
    public function listar_todos_los_usuarios()
    {
        $response = $this->get(route('usuarios'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('users');
    }

    /** @test */
    public function mostrar_usuario_por_id()
    {
        $user = User::first();
        $response = $this->get(route('usuario', ['id' => $user->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('user');
    }

    /** @test */
    public function listar_usuarios_por_nombre()
    {
        $response = $this->post(route('usuarios-nombre'), ['nombre' => 'Diego']);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('users');
    }

    /** @test */
    public function mostrar_formulario_crear_usuario()
    {
        $response = $this->get(route('crear.usuario'));
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function mostrar_formulario_actualizar_usuario()
    {
        $user = User::first();
        $response = $this->get(route('update.usuario', ['id' => $user->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('user');
    }

    /** @test */
    public function actualizar_usuario()
    {
        $user = User::latest()->first();
        $this->assertNotNull($user, 'No hay usuarios en la base de datos.');

        $response = $this->put(route('update.usuario', ['id' => $user->id]), [
            'name' => 'Usuario Actualizado',
            'email' => 'usuarioactualizado@gmail.com',
            'password' => 'nuevacontraseña',
            'foto' => null,
        ]);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function crear_usuario()
    {
        $response = $this->post(route('usuarios.store'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo.usuario@gmail.com',
            'password' => 'contraseña123',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('crear.usuario'));
        $this->assertDatabaseHas('users', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo.usuario@gmail.com',
        ]);
    }

/** @test */
    public function eliminar_usuario()
    {
        $user = User::latest()->first();
        $this->assertNotNull($user, 'No hay usuarios en la base de datos.');
        $user->foto = 'foto.jpg';
        $response = $this->delete(route('eliminar.usuario', ['id' => $user->id]));
        $response->assertRedirect(route('usuarios'));
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        Storage::disk('s3')->assertMissing('foto.jpg');
    }
}
