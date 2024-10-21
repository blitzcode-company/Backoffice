<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\User;
use Tests\TestCase;

class TransaccionControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function puede_filtrar_por_plan()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('transaccion.filtrar', ['plan' => 'Premium']));

        $response->assertStatus(200);
        $response->assertViewHas('transaccion', function ($transacciones) {
            return $transacciones->every(function ($transaccion) {
                return $transaccion->plan === 'Premium';
            });
        });
    }

    /** @test */
    public function puede_filtrar_por_id_de_usuario()
    {
        $user = User::first();
        $this->actingAs($user);

        $userId = 3;
        $response = $this->get(route('transaccion.filtrar', ['user_id' => $userId]));

        $response->assertStatus(200);
        $response->assertViewHas('transaccion', function ($transacciones) use ($userId) {
            return $transacciones->every(function ($transaccion) use ($userId) {
                return $transaccion->user_id == $userId;
            });
        });
    }

    /** @test */
    public function puede_filtrar_por_id_de_transaccion()
    {
        $user = User::first();
        $this->actingAs($user);

        $transaccionId = 1;
        $response = $this->get(route('transaccion.filtrar', ['id' => $transaccionId]));

        $response->assertStatus(200);
        $response->assertViewHas('transaccion', function ($transacciones) use ($transaccionId) {
            return $transacciones->every(function ($transaccion) use ($transaccionId) {
                return $transaccion->id == $transaccionId;
            });
        });
    }

    /** @test */
    public function puede_filtrar_por_estado_activo()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('transaccion.filtrar', ['estado' => 'activo']));

        $response->assertStatus(200);
        $response->assertViewHas('transaccion', function ($transacciones) {
            return $transacciones->every(function ($transaccion) {
                return is_null($transaccion->fecha_cancelacion);
            });
        });
    }

    /** @test */
    public function puede_filtrar_por_estado_cancelado()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('transaccion.filtrar', ['estado' => 'cancelado']));

        $response->assertStatus(200);
        $response->assertViewHas('transaccion', function ($transacciones) {
            return $transacciones->every(function ($transaccion) {
                return !is_null($transaccion->fecha_cancelacion);
            });
        });
    }
}
