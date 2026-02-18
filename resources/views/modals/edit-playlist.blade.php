<div class="modal fade" id="editPlaylistModal-{{ $playlistData['id'] }}" tabindex="-1" role="dialog"
    aria-labelledby="editPlaylistModalLabel-{{ $playlistData['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPlaylistModalLabel-{{ $playlistData['id'] }}">
                    <i class="fas fa-edit me-2"></i> Editar Playlist: {{ $playlistData['nombre'] }}
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('playlists.editar', ['id' => $playlistData['id']]) }}" method="POST">
                <div class="modal-body text-start">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombre-{{ $playlistData['id'] }}" class="form-label">Nombre de la Playlist:</label>
                        <input type="text" name="nombre" id="nombre-{{ $playlistData['id'] }}" class="form-control"
                            value="{{ $playlistData['nombre'] }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm btn-auto-width">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
