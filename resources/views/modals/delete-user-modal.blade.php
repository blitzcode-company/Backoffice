<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fas fa-user-times me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p class="mb-2">Para confirmar la eliminación, ingresa el ID del usuario en el siguiente campo:</p>
                    <p class="mb-2 text-primary font-weight-bold">ID: #{{ $user->id }}</p>
                    <input type="text" id="confirm-id-{{ $user->id }}" class="form-control"
                        placeholder="Ingresa el ID del usuario" required>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('usuario.eliminar', ['id' => $user->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" id="delete-btn-{{ $user->id }}" disabled>
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
        const modal = document.getElementById('confirmDeleteModal');
        if (modal) {
            const input = modal.querySelector('input[type="text"]');
            const button = modal.querySelector('button[type="submit"]');

            function toggleDeleteButton() {
                button.disabled = input.value !== '{{ $user->id }}';
            }
            input.addEventListener('input', toggleDeleteButton);

            modal.addEventListener('hidden.bs.modal', function () {
                if (document.querySelector('.modal-backdrop')) {
                    document.querySelector('.modal-backdrop').remove();
                }
            });
        }
    });
</script>
