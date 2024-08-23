<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function ListarTodosLosUsuarios()
    {
        $response = $this->get(route('usuarios'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('users');
    }

    /** @test */
    public function MostrarUsuarioPorId()
    {
        $user = User::first();
        $response = $this->get(route('usuario', ['id' => $user->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('user');
    }

    /** @test */
    public function ListarUsuariosPorNombre()
    {
        $response = $this->post(route('usuarios-nombre'), ['nombre' => 'Diego']);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('users');
    }

    /** @test */
    public function MostrarFormularioCrearUsuario()
    {
        $response = $this->get(route('crear.usuario'));
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function MostrarFormularioActualizarUsuario()
    {
        $user = User::first();

        $response = $this->get(route('update.usuario', ['id' => $user->id]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('user');
    }

    /** @test */
    public function ActualizarUsuario()
    {
        $user = User::latest()->first();
        $this->assertNotNull($user, 'No hay usuarios en la base de datos.');

        $response = $this->put(route('update.usuario', ['id' => $user->id]), [
            'name' => 'Usuario Actualizado',
            'email' => 'usuarioactualizado@example.com',
            'password' => 'nuevacontraseña',
            'foto' => null,
        ]);
        $response->assertSessionHas('success');
    }

    /** @test */
    public function EliminarUsuario()
    {
        $user = User::latest()->first();

        $response = $this->delete(route('eliminar.usuario', ['id' => $user->id]));

        $response->assertRedirect(route('usuarios'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
