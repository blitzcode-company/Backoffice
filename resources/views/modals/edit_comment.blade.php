<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">
                    <i class="fas fa-edit me-2"></i> Editar Comentario #{{ $comentario->id }}
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('comentarios.actualizar', ['comentario_id' => $comentario->id]) }}" method="POST">
                <div class="modal-body text-start">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Comentario:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required>{{ $comentario->mensaje }}</textarea>
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
