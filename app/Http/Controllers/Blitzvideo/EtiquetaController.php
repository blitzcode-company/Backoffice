<?php

namespace App\Http\Controllers\Blitzvideo;

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

        return redirect()->route('etiquetas')->with('success', "Etiqueta '{$etiqueta->nombre}' creada correctamente.");
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
            $etiqueta->delete();
            return redirect()->route('etiquetas')->with('success', "Etiqueta '{$nombreEtiqueta}' y sus asignaciones eliminadas correctamente.");
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('etiquetas')->with('error', 'La etiqueta no existe.');
        }
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
            $etiqueta->nombre = $request->nombre;
            $etiqueta->save();

            return redirect()->route('etiquetas')->with('success', "Etiqueta '{$etiqueta->nombre}' actualizada correctamente.");
        } catch (ModelNotFoundException $exception) {
            return redirect()->route('etiquetas')->with('error', 'La etiqueta no existe.');
        }
    }
}
