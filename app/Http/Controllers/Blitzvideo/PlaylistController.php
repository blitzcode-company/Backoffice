<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\Blitzvideo\Playlist;
use App\Models\Blitzvideo\Video;
use App\Traits\Paginable;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    use Paginable;

    public function formulario()
    {
        return view('playlists.create-playlist');
    }

    public function crearPlaylist(Request $request)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'acceso' => 'required|boolean',
                'user_id' => 'required|exists:users,id',
                'videos' => 'required|string',
            ]);

            $videoIds = explode(',', $data['videos']);

            $playlist = Playlist::create([
                'nombre' => $data['nombre'],
                'acceso' => $data['acceso'],
                'user_id' => $data['user_id'],
            ]);

            $playlist->videos()->sync($videoIds);
            return redirect()->route('playlists.crear.formulario')->with('success', 'Playlist creada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('playlists.crear.formulario')->with('error', 'Ocurrió un error al crear la playlist: ' . $e->getMessage());
        }
    }
    public function listarPlaylists(Request $request, $userId = null)
    {
        $nombre = $request->get('nombre');
        $query = Playlist::with('user:id,name')->withCount('videos');

        if (!empty($nombre)) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }
        $playlists = $this->paginateBuilder($query);
        $playlists->getCollection()->transform(function ($playlist) {
            return [
                'id' => $playlist->id,
                'nombre' => $playlist->nombre,
                'acceso' => $playlist->acceso,
                'cantidad_videos' => $playlist->videos_count,
                'propietario' => $playlist->user->name,
            ];
        });

        return view('playlists.playlists', compact('playlists'));
    }

    public function Buscar(Request $request)
    {
        $query = $request->get('q');

        $videos = Video::where('titulo', 'like', '%' . $query . '%')
            ->whereHas('canal.user', function ($query) {
                $query->where('name', '<>', 'Invitado');
            })
            ->latest()
            ->take(3)
            ->get();

        return response()->json($videos);
    }

    public function mostrarVideosDePlaylist($id)
    {
        $playlist = Playlist::with(['videos', 'user:id,name'])->findOrFail($id);
        $videos = $playlist->videos()->paginate(9);
        $playlistData = [
            'id' => $playlist->id,
            'nombre' => $playlist->nombre,
            'acceso' => $playlist->acceso,
            'propietario' => $playlist->user->name,
            'user_id' => $playlist->user->id,
        ];
        return view('playlists.playlist-videos', compact('playlistData', 'videos'));
    }

    public function cambiarAcceso(Request $request, $id)
    {
        $request->validate([
            'acceso' => 'required|boolean',
        ]);
        $playlist = Playlist::findOrFail($id);
        $playlist->acceso = $request->acceso;
        $playlist->save();

        return response()->json(['success' => true]);
    }

    public function editarNombrePlaylist(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);
        $playlist = Playlist::findOrFail($id);
        $playlist->nombre = $request->input('nombre');
        $playlist->save();
        return redirect()->route('playlists.videos', ['id' => $id])
            ->with('success', 'El nombre de la playlist ha sido actualizado correctamente.');
    }

    public function borrarPlaylist($id)
    {
        $playlist = Playlist::findOrFail($id);
        $playlist->delete();

        return redirect()->route('playlists.listar')
            ->with('success', 'La playlist ha sido eliminada correctamente.');
    }

}
