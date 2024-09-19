<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Etiqueta;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class EtiquetaControllerTest extends TestCase
{
    //use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

/** @test */
    public function puede_mostrar_vista_de_etiquetas()
    {
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
        $nuevaEtiquetaData = ['nombre' => 'Nueva Etiqueta'];

        $response = $this->post(route('etiquetas.crear'), $nuevaEtiquetaData);

        $response->assertRedirect(route('etiquetas.listar'));
        $this->assertDatabaseHas('etiquetas', ['nombre' => 'Nueva Etiqueta']);
    }

    /** @test */
    public function puede_actualizar_etiqueta()
    {
        $etiqueta = Etiqueta::first();
        $nuevaData = ['nombre' => 'Etiqueta Actualizada'];

        $response = $this->put(route('etiquetas.editar', $etiqueta->id), $nuevaData);

        $response->assertRedirect(route('etiquetas.listar'));
        $this->assertDatabaseHas('etiquetas', ['id' => $etiqueta->id, 'nombre' => 'Etiqueta Actualizada']);
    }

    /** @test */
    public function puede_eliminar_etiqueta_y_sus_asignaciones()
    {
        $etiqueta = Etiqueta::latest()->first();

        $response = $this->delete(route('etiquetas.eliminar', $etiqueta->id));

        $response->assertRedirect(route('etiquetas.listar'));
        $this->assertDatabaseMissing('etiquetas', ['id' => $etiqueta->id]);
    }
}
