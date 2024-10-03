<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Suscribe;
use App\Models\Blitzvideo\User;
use Tests\TestCase;

class SuscriptoresControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function puede_listar_suscriptores()
    {
        $user = User::first();
        $this->actingAs($user);

        $canal = Canal::findOrFail(7);
        $response = $this->get(route('suscriptores.listar', ['id' => $canal->id]));
        $response->assertStatus(200);
        $response->assertViewIs('canales.listar-suscriptores');
        $response->assertViewHas('canal', $canal);
        $response->assertViewHas('suscriptores');
    }

    /** @test */
    public function puede_listar_suscriptores_por_nombre()
    {
        $user = User::first();
        $this->actingAs($user);
        $canal = Canal::findOrFail(7);
        $response = $this->get(route('suscriptores.nombre', ['id' => $canal->id, 'nombre' => 'Diego']));
        $response->assertStatus(200);
        $response->assertViewIs('canales.listar-suscriptores');
        $response->assertViewHas('suscriptores');
    }

    /** @test */
    public function puede_suscribirse_a_un_canal()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->post(route('suscriptores.suscribir', ['canalId' => '7']), ['usuario_id' => '11']);
        $suscribe = Suscribe::first();
        $response->assertRedirect(route('suscriptores.listar', ['id' => '7']));
        $this->assertDatabaseHas('suscribe', ['id' => $suscribe->id]);
    }

    /** @test */
    public function puede_desuscribirse_de_un_canal()
    {
        $user = User::first();
        $this->actingAs($user);

        $canal = Canal::findOrFail(7);
        $suscriptor = Suscribe::where('canal_id', $canal->id)->orderBy('id', 'desc')->firstOrFail();
        $response = $this->delete(route('suscriptores.desuscribir', ['canalId' => $canal->id, 'suscribeId' => $suscriptor->id]));
        $response->assertRedirect(route('suscriptores.listar', ['id' => $canal->id]));
        $this->assertNotNull(Suscribe::withTrashed()->find($suscriptor->id)->deleted_at);
    }

}
