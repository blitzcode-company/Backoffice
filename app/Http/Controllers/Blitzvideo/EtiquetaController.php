<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Models\Blitzvideo\Etiqueta;
use App\Models\Blitzvideo\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EtiquetaController extends Controller
{
    public function AsignarEtiquetas(Request $request, $idVideo)
    {
        try {
            $video = Video::findOrFail($idVideo);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'El video no existe');
        }
        $etiquetas = $request->input('etiquetas');
        $video->etiquetas()->sync($etiquetas);
    }

    public function ListarVideosPorEtiqueta(Request $request, $idEtiqueta)
    {
        $etiqueta = Etiqueta::findOrFail($idEtiqueta);
        $videos = $etiqueta->videos()->get();
        return view('videos.lista', compact('videos', 'etiqueta'));
    }

    public function ListarEtiquetas()
    {
        $etiquetas = Etiqueta::all();
        return $etiquetas;
    }

    public function FiltrarVideosPorEtiquetaYCanal($etiquetaId, $canalId)
    {
        $videos = Video::where('canal_id', $canalId)
            ->whereHas('etiquetas', function ($query) use ($etiquetaId) {
                $query->where('etiquetas.id', $etiquetaId);
            })
            ->get();
        return view('videos.filtrados', compact('videos'));
    }
}
