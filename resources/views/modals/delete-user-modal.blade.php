<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                    aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres eliminar este usuario?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('usuario.eliminar', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function () {
        document.querySelector('.modal-backdrop').remove();
    });
</script>
