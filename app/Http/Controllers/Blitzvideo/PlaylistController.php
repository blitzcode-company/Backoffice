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

    public function create()
    {
        return view('playlists.create-playlist');
    }

    public function listarPlaylists(Request $request)
    {
        $nombre = $request->get('nombre');
        $query = Playlist::with('user:id,name')->withCount('videos');

        if (!empty($nombre)) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }
        $playlists = $this->paginateBuilder($query);
        $playlists->getCollection()->transform(function ($playlist) {
            return [
                'nombre' => $playlist->nombre,
                'acceso' => $playlist->acceso,
                'cantidad_videos' => $playlist->videos_count,
                'propietario' => $playlist->user->name,
            ];
        });
        return view('playlists.playlists', compact('playlists'));
    }

}
