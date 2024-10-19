<div class="modal fade" id="editPlaylistModal-{{ $playlistData['id'] }}" tabindex="-1" role="dialog"
    aria-labelledby="editPlaylistModalLabel-{{ $playlistData['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlaylistModalLabel-{{ $playlistData['id'] }}">Editar Playlist:
                    {{ $playlistData['nombre'] }}</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('playlists.editar', ['id' => $playlistData['id']]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nombre-{{ $playlistData['id'] }}">Nombre de la Playlist:</label>
                        <input type="text" name="nombre" id="nombre-{{ $playlistData['id'] }}" class="form-control"
                            value="{{ $playlistData['nombre'] }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
