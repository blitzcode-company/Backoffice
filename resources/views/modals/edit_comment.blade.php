<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Editar Comentario #{{ $comentario->id }}</h5>
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                
            </div>
            <div class="modal-body">
                <form action="{{ route('comentarios.actualizar', ['comentario_id' => $comentario->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="mensaje">Comentario:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required>{{ $comentario->mensaje }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
