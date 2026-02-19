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

    public function exportar(Request $request)
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

        $transacciones = $query->get();
        $filename = "transacciones_" . date('Y-m-d_H-i') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($transacciones) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID', 'Plan', 'Metodo de Pago', 'Fecha Inicio', 'Fecha Cancelacion', 'Estado', 'ID Usuario']);

            foreach ($transacciones as $t) {
                $estadoStr = $t->fecha_cancelacion ? 'Cancelado' : 'Activo';
                fputcsv($file, [$t->id, $t->plan, $t->metodo_de_pago, $t->fecha_inicio, $t->fecha_cancelacion, $estadoStr, $t->user_id]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
