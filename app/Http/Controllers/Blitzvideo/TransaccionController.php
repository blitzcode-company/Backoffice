<?php

namespace App\Http\Controllers\Blitzvideo;
use Illuminate\Http\Request;
use App\Models\Blitzvideo\Transaccion;
use App\Http\Controllers\Controller;

class TransaccionController extends Controller
{
    public function filtrar(Request $request)
    {
        $plan = $request->input('plan');
        $userId = $request->input('user_id');
        $planId = $request->input('id');
        $estado = $request->input('estado');
        $query = Transaccion::query();

        if ($plan) {
            $query->where('plan', 'like', '%' . $plan . '%');
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($planId) {
            $query->where('id', $planId);
        }

        if ($estado) {
            if ($estado === 'activo') {
                $query->whereNull('fecha_cancelacion');
            } elseif ($estado === 'cancelado') {
                $query->whereNotNull('fecha_cancelacion');
            }
        }
        $transaccion = $query->get();
        return view('transaccion.listar-transacciones', compact('transaccion'));
    }
}
