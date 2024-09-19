<div class="modal fade" id="confirmDeleteModal-{{ $canal->id }}" tabindex="-1" role="dialog"
    aria-labelledby="confirmDeleteModalLabel-{{ $canal->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel-{{ $canal->id }}">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres eliminar el canal?</p><br>
                <strong>{{ $canal->nombre }}</strong><br><br>
                <p>Para confirmar la eliminación, ingresa el ID del canal en el siguiente campo:</p><br>
                <p> <strong> ID: #{{ $canal->id }}</strong></p>
                <input type="text" id="confirm-id-{{ $canal->id }}" class="form-control"
                    placeholder="Ingresa el ID del canal" required>
            </div>
            <div class="modal-footer">
                <form action="{{ route('canal.eliminar', ['id' => $canal->id]) }}" method="POST"
                    id="delete-form-{{ $canal->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="delete-btn-{{ $canal->id }}" disabled>
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.modal').forEach(modal => {
            const input = modal.querySelector('input');
            const button = modal.querySelector('button[type="submit"]');
            const modalId = input.id.split('-')[2];

            input.addEventListener('input', function() {
                if (input.value === modalId) {
                    button.disabled = false;
                } else {
                    button.disabled = true;
                }
            });
        });
    });
</script>
