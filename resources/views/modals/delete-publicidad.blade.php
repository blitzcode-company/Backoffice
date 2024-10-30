<div class="modal fade" id="confirmDeleteModal-{{ $publicidad->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel-{{ $publicidad->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel-{{ $publicidad->id }}">Confirmar Eliminación</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar la publicidad de la empresa <strong>{{ $publicidad->empresa }}</strong>?</p>
                <p>Para confirmar la eliminación, ingresa el ID de la publicidad en el siguiente campo:</p><br>
                <p><strong>ID: #{{ $publicidad->id }}</strong></p>
                <input type="text" id="confirm-id-{{ $publicidad->id }}" class="form-control" placeholder="Ingresa el ID de la publicidad" required>
            </div>
            <div class="modal-footer">
                <form action="{{ route('publicidad.eliminar', ['id' => $publicidad->id]) }}" method="POST" id="delete-form-{{ $publicidad->id }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <button type="submit" class="btn btn-danger" id="delete-btn-{{ $publicidad->id }}" disabled>
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.modal').forEach(modal => {
            const input = modal.querySelector('input[type="text"]');
            const button = modal.querySelector('button[type="submit"]');
            const modalId = input.id.split('-')[2];

            input.addEventListener('input', function() {
                button.disabled = input.value !== modalId;
            });
        });
    });
</script>