<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Publicidad;
use App\Models\Blitzvideo\User;
use App\Traits\Paginable;
use Tests\TestCase;

class PublicidadTest extends TestCase
{

    protected $user;
    use Paginable;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function mostrar_formulario_crear_publicidad()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->get(route('publicidad.crear.formulario'));
        $response->assertStatus(200)
            ->assertViewIs('publicidad.crear-publicidad');
    }

    /** @test */
    public function mostrar_formulario_editar_publicidad()
    {
        $user = User::first();
        $this->actingAs($user);
        $publicidad = Publicidad::first();

        $response = $this->get(route('publicidad.editar.formulario', ['id' => $publicidad->id]));

        $response->assertStatus(200)
            ->assertViewIs('publicidad.editar-publicidad')
            ->assertViewHas('publicidad', $publicidad);
    }

    /** @test */
    public function crear_publicidad()
    {
        $user = User::first();
        $this->actingAs($user);

        $data = [
            'empresa' => 'Empresa de prueba',
            'prioridad' => 2,
            'video_id' => 5,
        ];

        $response = $this->post(route('publicidad.crear'), $data);

        $response->assertRedirect(route('publicidad.crear.formulario'))
            ->assertSessionHas('success', 'Publicidad creada exitosamente y asociada al video');

        $this->assertDatabaseHas('publicidad', [
            'empresa' => 'Empresa de prueba',
            'prioridad' => 2,
        ]);

        $publicidad = Publicidad::where('empresa', 'Empresa de prueba')->first();
        $this->assertDatabaseHas('video_publicidad', [
            'publicidad_id' => $publicidad->id,
            'video_id' => 5,
        ]);

    }

    /** @test */
    public function modificar_publicidad()
    {
        $user = User::first();
        $this->actingAs($user);
        $publicidad = Publicidad::where('empresa', 'Empresa de prueba')->first();

        $updateData = [
            'empresa' => 'Empresa Modificada',
            'prioridad' => 1,
        ];
        $response = $this->put(route('publicidad.editar', ['id' => $publicidad->id]), $updateData);

        $response->assertRedirect(route('publicidad.editar.formulario', ['id' => $publicidad->id]))
            ->assertSessionHas('success', 'Publicidad modificada exitosamente');

        $this->assertDatabaseHas('publicidad', [
            'id' => $publicidad->id,
            'empresa' => 'Empresa Modificada',
            'prioridad' => 1,
        ]);
    }

    /** @test */
    public function listar_publicidades()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->get(route('publicidad.listar'));
        $response->assertStatus(200);
        $response->assertViewIs('publicidad.publicidades');
        $response->assertViewHas('publicidades');
    }

/** @test */
    public function listar_publicidades_filtradas_por_nombre()
    {
        $user = User::first();
        $this->actingAs($user);
        $publicidad = Publicidad::where('empresa', 'Empresa Modificada')->first();
        $response = $this->get(route('publicidad.listar', ['nombre' => 'Empresa Modificada']));
        $response->assertStatus(200);
        $response->assertViewIs('publicidad.publicidades');
        $response->assertViewHas('publicidades', function ($publicidades) use ($publicidad) {
            return $publicidades->contains('id', $publicidad->id);
        });
    }

    /** @test */
    public function eliminar_publicidad_logica()
    {
        $user = User::first();
        $this->actingAs($user);

        $publicidad = Publicidad::first();
        $response = $this->delete(route('publicidad.eliminar', ['id' => $publicidad->id]));
        $response->assertRedirect(route('publicidad.listar'))
            ->assertSessionHas('mensaje', 'Publicidad eliminada exitosamente');
        $this->assertSoftDeleted('publicidad', [
            'id' => $publicidad->id,
        ]);
    }

}
