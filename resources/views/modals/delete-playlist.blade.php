<div class="modal fade" id="confirmDeleteModal-{{ $playlistData['id'] }}" tabindex="-1"
    aria-labelledby="confirmDeleteModalLabel-{{ $playlistData['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel-{{ $playlistData['id'] }}">
                    <i class="fas fa-trash-alt me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p class="mb-2">Para confirmar la eliminación, ingresa el ID de la playlist en el siguiente campo:</p>
                    <p class="mb-2 text-primary font-weight-bold">ID: #{{ $playlistData['id'] }}</p>
                    <input type="text" id="confirm-id-{{ $playlistData['id'] }}" class="form-control"
                        placeholder="Ingresa el ID de la playlist" required>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('playlists.eliminar', ['id' => $playlistData['id']]) }}" method="POST"
                    id="delete-form-{{ $playlistData['id'] }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-danger btn-sm" id="delete-btn-{{ $playlistData['id'] }}" disabled>
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('confirmDeleteModal-{{ $playlistData['id'] }}');
        if (modal) {
            const input = modal.querySelector('input[type="text"]');
            const button = modal.querySelector('button[type="submit"]');

            function toggleDeleteButton() {
                button.disabled = input.value !== '{{ $playlistData['id'] }}';
            }
            input.addEventListener('input', toggleDeleteButton);
        }
    });
</script>
