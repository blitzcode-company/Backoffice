<?php

namespace Tests\Feature;

use App\Events\ActividadRegistrada;
use App\Models\Blitzvideo\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
        Storage::fake('s3');
    }

    /** @test */
    public function listar_todos_los_usuarios()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('usuario.listar'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('users');
    }

    /** @test */
    public function mostrar_usuario_por_id()
    {
        $user = User::first();
        $this->actingAs($user);

        $user = User::first();
        $response = $this->get(route('usuario.detalle', ['id' => $user->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('user');
    }

/** @test */
    public function listar_usuarios_por_nombre()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('usuario.nombre', ['nombre' => 'Diego']));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('usuario.usuarios');
        $response->assertViewHas('users', function ($viewUsers) {
            return $viewUsers->contains(function ($user) {
                return $user->name === 'Diego';
            });
        });
    }

    /** @test */
    public function mostrar_formulario_crear_usuario()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('usuario.crear.formulario'));
        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function mostrar_formulario_actualizar_usuario()
    {
        $user = User::first();
        $this->actingAs($user);

        $user = User::first();
        $response = $this->get(route('usuario.editar.formulario', ['id' => $user->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('user');
    }

    /** @test */
    public function crear_usuario()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->post(route('usuario.crear'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo.usuario@gmail.com',
            'fecha_de_nacimiento' => '1985-08-30',
            'password' => 'contraseña123',
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo.usuario@gmail.com',
            'fecha_de_nacimiento' => '1985-08-30',
        ]);
    }

/** @test */
    public function actualizar_usuario()
    {
        $user = User::first();
        $this->actingAs($user);

        $userToUpdate = User::latest()->first();
        $response = $this->put(route('usuario.editar', ['id' => $userToUpdate->id]), [
            'name' => 'Usuario Actualizado',
            'email' => 'usuarioactualizado@gmail.com',
            'password' => 'nuevacontraseña',
            'foto' => null,
        ]);
        $response->assertStatus(302);
        $updatedUser = User::find($userToUpdate->id);
        $this->assertEquals('Usuario Actualizado', $updatedUser->name);
        $this->assertEquals('usuarioactualizado@gmail.com', $updatedUser->email);
        $this->assertNotEquals('contraseña', $updatedUser->password);
    }

/** @test */
    public function eliminar_usuario()
    {
        $user = User::first();
        $this->actingAs($user);

        $user = User::latest()->first();
        $this->assertNotNull($user, 'No hay usuarios en la base de datos.');
        $user->foto = 'foto.jpg';
        $response = $this->delete(route('usuario.eliminar', ['id' => $user->id]));
        $response->assertStatus(302);
        $this->assertSoftDeleted('users', ['id' => $user->id]);
        Storage::disk('s3')->assertMissing('foto.jpg');
    }

    /** @test */
    public function puede_bloquear_usuario()
    {
        Mail::fake();
        Event::fake();

        $user = User::first();
        $this->actingAs($user);

        $usuarioABloquear = User::findOrFail(2);
        $response = $this->post(route('usuario.bloquear', ['id' => $usuarioABloquear->id]), [
            'motivo' => 'Incumplimiento de políticas',
        ]);
        $response->assertStatus(302);
        $usuarioABloquear->refresh();
        $this->assertEquals(1, $usuarioABloquear->bloqueado);
        Mail::assertNothingSent();
        Event::assertDispatched(ActividadRegistrada::class, function ($event) use ($usuarioABloquear) {
            return $event->detalles === "Se bloqueó el usuario con ID: {$usuarioABloquear->id}";
        });
    }

/** @test */
    public function puede_desbloquear_usuario()
    {
        Event::fake();
        $user = User::first();
        $this->actingAs($user);

        $usuarioADesbloquear = User::findOrFail(2);
        $response = $this->post(route('usuario.desbloquear', ['id' => $usuarioADesbloquear->id]));

        $response->assertStatus(302);
        $usuarioADesbloquear->refresh();
        $this->assertEquals(0, $usuarioADesbloquear->bloqueado);
        Event::assertDispatched(ActividadRegistrada::class, function ($event) use ($usuarioADesbloquear) {
            return $event->detalles === "Se desbloqueó el usuario con ID: {$usuarioADesbloquear->id}";
        });
    }

}
