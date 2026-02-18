<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                    aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="m-0">Para confirmar la eliminación, ingresa el ID del usuario en el siguiente campo:</p>
                <br>
                <p class="m-0"><strong>ID: #{{ $user->id }}</strong></p>
                <input type="text" id="confirm-id-{{ $user->id }}" class="form-control"
                    placeholder="Ingresa el ID del usuario" required>
            </div>
            <div class="modal-footer">
                <form action="{{ route('usuario.eliminar', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="delete-btn-{{ $user->id }}" disabled>
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
        const modal = document.getElementById('confirmDeleteModal');
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
    });
</script>
