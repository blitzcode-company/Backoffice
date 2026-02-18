<div class="modal fade" id="restoreResponseModal{{ $respuesta->id }}" tabindex="-1"
    aria-labelledby="restoreResponseModalLabel{{ $respuesta->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreResponseModalLabel{{ $respuesta->id }}">
                    <i class="fas fa-trash-restore me-2"></i> Restaurar Respuesta</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3">
                    <i class="fas fa-undo-alt text-success fa-3x mb-3"></i>
                <p>¿Estás seguro de que deseas restaurar esta respuesta?</p>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('comentarios.restaurar', ['comentario_id' => $respuesta->id]) }}" method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-undo"></i> Restaurar
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
