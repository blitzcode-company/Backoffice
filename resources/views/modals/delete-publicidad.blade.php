<div class="modal fade" id="confirmDeleteModal-{{ $publicidad->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel-{{ $publicidad->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel-{{ $publicidad->id }}">
                    <i class="fas fa-trash-alt me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p>¿Estás seguro de que deseas eliminar la publicidad de la empresa <strong>{{ $publicidad->empresa }}</strong>?</p>
                </div>
                <div class="mb-3">
                    <p class="mb-2">Para confirmar la eliminación, ingresa el ID de la publicidad en el siguiente campo:</p>
                    <p class="mb-2 text-primary font-weight-bold">ID: #{{ $publicidad->id }}</p>
                    <input type="text" id="confirm-id-{{ $publicidad->id }}" class="form-control" placeholder="Ingresa el ID de la publicidad" required>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('publicidad.eliminar', ['id' => $publicidad->id]) }}" method="POST" id="delete-form-{{ $publicidad->id }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <button type="submit" class="btn btn-danger btn-sm" id="delete-btn-{{ $publicidad->id }}" disabled>
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
        const modal = document.getElementById('confirmDeleteModal-{{ $publicidad->id }}');
        if (modal) {
            const input = document.getElementById('confirm-id-{{ $publicidad->id }}');
            const button = document.getElementById('delete-btn-{{ $publicidad->id }}');
            const targetId = '{{ $publicidad->id }}';

            input.addEventListener('input', function() {
                button.disabled = input.value !== targetId;
            });
        }
    });
</script>