<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Events\ActividadRegistrada;
use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Etiqueta;
use App\Models\Blitzvideo\Video;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EtiquetaController extends Controller
{
    public function ListarEtiquetas()
    {
        $etiquetas = Etiqueta::on('blitzvideo')->get();
        return $etiquetas;
    }

    public function MostrarEtiquetas()
    {
        $etiquetas = Etiqueta::on('blitzvideo')->orderBy('id', 'desc')->get();
        return view('etiquetas', compact('etiquetas'));
    }

    public function CrearEtiqueta(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blitzvideo.etiquetas', 'nombre'),
            ],
        ]);
        $etiqueta = Etiqueta::create([
            'nombre' => $request->nombre,
        ]);
        $this->registrarActividadCrearEtiqueta($etiqueta);
        return redirect()->route('etiquetas.listar')->with('success', "Etiqueta '{$etiqueta->nombre}' creada correctamente.");
    }

    private function registrarActividadCrearEtiqueta(Etiqueta $etiqueta)
    {
        $detalles = sprintf(
            'ID etiqueta: %d; Nombre etiqueta: %s;',
            $etiqueta->id,
            $etiqueta->nombre
        );
        event(new ActividadRegistrada('Etiqueta creada', $detalles));
    }

    public function AsignarEtiquetas(Request $request, $idVideo)
    {
        try {
            $video = Video::on('blitzvideo')->findOrFail($idVideo);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'El video no existe');
        }

        $etiquetas = $request->input('etiquetas');
        $video->etiquetas()->sync($etiquetas);
    }

    public function EliminarEtiquetaYAsignaciones($id)
    {
        try {
            $etiqueta = Etiqueta::on('blitzvideo')->findOrFail($id);
            $nombreEtiqueta = $etiqueta->nombre;
            $etiqueta->videos()->detach();
            $this->registrarActividadEliminarEtiqueta($etiqueta);
            $etiqueta->delete();
            return redirect()->route('etiquetas.listar')->with('success', "Etiqueta '{$nombreEtiqueta}' y sus asignaciones eliminadas correctamente.");
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('etiquetas.listar')->with('error', 'La etiqueta no existe.');
        }
    }

    private function registrarActividadEliminarEtiqueta(Etiqueta $etiqueta)
    {
        $detalles = sprintf(
            'ID etiqueta: %d; Nombre: %s;',
            $etiqueta->id,
            $etiqueta->nombre
        );
        event(new ActividadRegistrada('Etiqueta eliminada', $detalles));
    }

    public function ActualizarEtiqueta(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('blitzvideo.etiquetas', 'nombre')->ignore($id),
            ],
        ]);

        try {
            $etiqueta = Etiqueta::on('blitzvideo')->findOrFail($id);
            $nombreAnterior = $etiqueta->nombre;
            $etiqueta->nombre = $request->nombre;
            $etiqueta->save();
            $this->registrarActividadActualizarEtiqueta($etiqueta, $nombreAnterior);
            return redirect()->route('etiquetas.listar')->with('success', "Etiqueta '{$etiqueta->nombre}' actualizada correctamente.");
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('etiquetas.listar')->with('error', 'La etiqueta no existe.');
        }
    }

    private function registrarActividadActualizarEtiqueta(Etiqueta $etiqueta, $nombreAnterior)
    {
        $detalles = sprintf(
            'ID etiqueta: %d; %s -> %s;',
            $etiqueta->id,
            $nombreAnterior,
            $etiqueta->nombre
        );

        event(new ActividadRegistrada('Etiqueta actualizada', $detalles));
    }

}
