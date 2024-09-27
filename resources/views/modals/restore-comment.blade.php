<div class="modal fade" id="restoreCommentModal{{ $comentario->id }}" tabindex="-1" role="dialog"
    aria-labelledby="restoreCommentModalLabel{{ $comentario->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreCommentModalLabel{{ $comentario->id }}">Restaurar Comentario</h5>
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas restaurar este comentario?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('comentarios.restaurar', ['comentario_id' => $comentario->id]) }}" method="POST"
                    class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-undo"></i> Restaurar
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
