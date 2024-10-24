<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Publicidad;
use App\Traits\Paginable;
use Illuminate\Http\Request;

class PublicidadController extends Controller
{
    use Paginable;

    public function formulario()
    {
        return view('publicidad.crear-publicidad');
    }

    public function formularioEditar($id)
    {
        $publicidad = Publicidad::findOrFail($id);
        return view('publicidad.editar-publicidad', compact('publicidad'));
    }

    public function crearPublicidad(Request $request)
    {
        $request->validate([
            'empresa' => 'required|string|max:255',
            'prioridad' => 'required|integer|in:1,2,3',
            'video_id' => 'required',
        ]);
        $publicidad = new Publicidad();
        $publicidad->empresa = $request->empresa;
        $publicidad->prioridad = $request->prioridad;
        $publicidad->save();
        $publicidad->video()->attach($request->input('video_id'));
        return redirect()->route('publicidad.crear.formulario')
            ->with('success', 'Publicidad creada exitosamente y asociada al video');
    }

    public function modificarPublicidad(Request $request, $id)
    {
        $request->validate([
            'empresa' => 'required|string|max:255',
            'prioridad' => 'required|integer|in:1,2,3',
        ]);
        $publicidad = Publicidad::findOrFail($id);
        $publicidad->empresa = $request->empresa;
        $publicidad->prioridad = $request->prioridad;
        $publicidad->save();
        return redirect()->route('publicidad.editar.formulario', ['id' => $publicidad->id])
            ->with('success', 'Publicidad modificada exitosamente');
    }
/*


FALTA BORRA PUBLICIDAD


    public function eliminarPublicidad($id)
    {
        $publicidad = Publicidad::findOrFail($id);
        $publicidad->delete();

        return redirect()->route('publicidades.listar')
            ->with('mensaje', 'Publicidad eliminada exitosamente');
    }
*/
    public function listarPublicidades(Request $request)
    {
        $nombre = $request->input('nombre');
        $page = $request->input('page', 1);
        $publicidades = $this->obtenerPublicidadesPorNombre($nombre);
        $publicidades = $this->paginateCollection($publicidades, 6, $page);
        return view('publicidad.publicidades', compact('publicidades'));
    }

    private function obtenerPublicidadesPorNombre($nombre)
    {
        if ($nombre) {
            return Publicidad::with('video')
                ->where('empresa', 'LIKE', '%' . $nombre . '%')
                ->get();
        }
        return Publicidad::with('video')->get();
    }

    public function contarVistasPublicidad($publicidadId, $userId)
    {

    }
}
