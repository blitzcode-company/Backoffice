<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Etiqueta;
use App\Models\Blitzvideo\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EtiquetaControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
        Event::fake();
    }

/** @test */
    public function puede_mostrar_vista_de_etiquetas()
    {   
        $user = User::first();
        $this->actingAs($user);

        $etiquetas = Etiqueta::on('blitzvideo')->orderBy('id', 'desc')->get();
        $response = $this->get(route('etiquetas.listar'));
        $response->assertStatus(200);
        $response->assertViewIs('etiquetas');
        $response->assertViewHas('etiquetas', function ($viewEtiquetas) use ($etiquetas) {
            foreach ($etiquetas as $etiqueta) {
                if (!$viewEtiquetas->contains('id', $etiqueta->id)) {
                    return false;
                }
            }
            return true;
        });
    }

    /** @test */
    public function puede_crear_una_nueva_etiqueta()
    {
        $user = User::first();
        $this->actingAs($user);

        $nuevaEtiquetaData = ['nombre' => 'Nueva Etiqueta'];
        $response = $this->post(route('etiquetas.crear'), $nuevaEtiquetaData);
        $response->assertRedirect(route('etiquetas.listar'));
        $this->assertDatabaseHas('etiquetas', ['nombre' => 'Nueva Etiqueta']);
    }

    /** @test */
    public function puede_actualizar_etiqueta()
    {
        $user = User::first();
        $this->actingAs($user);

        $etiqueta = Etiqueta::first();
        $nuevaData = ['nombre' => 'Etiqueta Actualizada'];
        $response = $this->put(route('etiquetas.editar', $etiqueta->id), $nuevaData);
        $response->assertRedirect(route('etiquetas.listar'));
        $this->assertDatabaseHas('etiquetas', ['id' => $etiqueta->id, 'nombre' => 'Etiqueta Actualizada']);
    }

    /** @test */
    public function puede_eliminar_etiqueta_y_sus_asignaciones()
    {
        $user = User::first();
        $this->actingAs($user);

        $etiqueta = Etiqueta::latest()->first();
        $response = $this->delete(route('etiquetas.eliminar', $etiqueta->id));
        $response->assertRedirect(route('etiquetas.listar'));
        $this->assertDatabaseMissing('etiquetas', ['id' => $etiqueta->id]);
    }
}
