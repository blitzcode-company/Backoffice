<div class="modal fade" id="replyCommentModal" tabindex="-1" role="dialog" aria-labelledby="replyCommentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyCommentModalLabel">Responder Comentario</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('comentarios.responder') }}" method="POST">
                    @csrf
                    <input type="hidden" name="video_id" value="{{ $video->id }}">
                    <div class="form-group">
                        <label for="usuario_id">ID del Usuario:</label>
                        <input type="number" class="form-control" id="usuario_id" name="usuario_id" required
                            min="1">
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Comentario:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="respuesta_id" name="respuesta_id" value="{{ $comentario->id }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Guardar Comentario
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
