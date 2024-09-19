<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Comentario;
use App\Models\Blitzvideo\Video;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{

    public function ListarComentarios($video_id)
    {
        $video = Video::findOrFail($video_id);
        $comentarios = Comentario::withTrashed()
            ->where('video_id', $video_id)
            ->whereNull('respuesta_id')
            ->with(['user', 'respuestas' => function ($query) {
                $query->withTrashed()->with('user');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('comentarios.listado', compact('video', 'comentarios'));
    }
    public function VerComentario($comentario_id)
    {
        $comentario = Comentario::withTrashed()
            ->where('id', $comentario_id)
            ->with(['user', 'respuestas' => function ($query) {
                $query->withTrashed()->with('user');
            }])
            ->firstOrFail();

        $video = $comentario->video;
        return view('comentarios.ver', compact('comentario', 'video'));
    }

    private function guardarComentario(Request $request, $video_id = null)
    {
        $request->validate([
            'usuario_id' => 'required|integer',
            'mensaje' => 'required|string|max:1000',
            'video_id' => 'required|integer',
            'respuesta_id' => 'nullable|integer',
        ]);

        $respuesta_id = $request->input('respuesta_id');

        if ($respuesta_id) {
            $comentarioPadre = Comentario::withTrashed()->find($respuesta_id);

            if (!$comentarioPadre) {
                return redirect()->back()->withErrors(['error' => 'El comentario al que intentas responder no existe.']);
            }

            if ($comentarioPadre->trashed()) {
                return redirect()->back()->withErrors(['error' => 'El comentario al que intentas responder ha sido eliminado y necesita ser restaurado.']);
            }

            $video_id = $video_id ?? $comentarioPadre->video_id;
        }
        if (!$video_id) {
            return redirect()->back()->withErrors(['error' => 'El ID del video no puede ser nulo.']);
        }

        $comentario = new Comentario();
        $comentario->usuario_id = $request->input('usuario_id');
        $comentario->mensaje = $request->input('mensaje');
        $comentario->video_id = $video_id;
        $comentario->respuesta_id = $respuesta_id;
        $comentario->bloqueado = false;
        $comentario->save();

        return $comentario;
    }
    public function crearComentario(Request $request)
    {
        $resultado = $this->guardarComentario($request, $request->input('video_id'));

        if ($resultado instanceof \Illuminate\Http\RedirectResponse) {
            return $resultado;
        }

        return redirect()->route('comentarios.listado', ['id' => $request->input('video_id')])
            ->with('success', 'Comentario creado exitosamente.');
    }

    public function responderComentario(Request $request)
    {
        $resultado = $this->guardarComentario($request);

        if ($resultado instanceof \Illuminate\Http\RedirectResponse) {
            return $resultado;
        }

        return redirect()->route('comentarios.ver', ['comentario_id' => $request->input('respuesta_id')])
            ->with('success', 'Respuesta creada exitosamente.');
    }

    public function eliminarComentario($comentario_id)
    {
        $comentario = Comentario::findOrFail($comentario_id);
        $comentario->delete();

        return redirect()->back()->with('success', 'Comentario eliminado exitosamente.');
    }

    public function restaurarComentario($comentario_id)
    {
        $comentario = Comentario::withTrashed()->findOrFail($comentario_id);
        if ($comentario->trashed()) {
            $comentario->restore();
            return redirect()->back()->with('success', 'Comentario restaurado correctamente.');
        }
        return redirect()->back()->with('info', 'El comentario no estaba eliminado.');
    }

    public function actualizarComentario(Request $request, $comentario_id)
    {
        $request->validate([
            'mensaje' => 'required|string|max:1000',
        ]);
        $comentario = Comentario::withTrashed()->findOrFail($comentario_id);
        $comentario->mensaje = $request->input('mensaje');
        $comentario->save();
        return redirect()->route('comentarios.ver', ['comentario_id' => $comentario_id])
            ->with('success', 'Comentario actualizado exitosamente.');
    }
    public function bloquearComentario($comentario_id)
    {
        $comentario = Comentario::withTrashed()->findOrFail($comentario_id);

        if ($comentario->bloqueado) {
            return redirect()->back()->with('info', 'El comentario ya está bloqueado.');
        }

        $comentario->bloqueado = true;
        $comentario->save();

        return redirect()->back()->with('success', 'Comentario bloqueado exitosamente.');
    }
    public function desbloquearComentario($comentario_id)
    {
        $comentario = Comentario::withTrashed()->findOrFail($comentario_id);

        if (!$comentario->bloqueado) {
            return redirect()->back()->with('info', 'El comentario no está bloqueado.');
        }

        $comentario->bloqueado = false;
        $comentario->save();

        return redirect()->back()->with('success', 'Comentario desbloqueado exitosamente.');
    }

}
