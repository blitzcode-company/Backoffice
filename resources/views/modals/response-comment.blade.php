<div class="modal fade" id="replyCommentModal" tabindex="-1" role="dialog" aria-labelledby="replyCommentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyCommentModalLabel">
                    <i class="fas fa-reply me-2"></i> Responder Comentario
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('comentarios.responder') }}" method="POST">
                <div class="modal-body text-start">
                    @csrf
                    <input type="hidden" name="video_id" value="{{ $video->id }}">
                    <div class="mb-3">
                        <label for="usuario_id" class="form-label">ID del Usuario:</label>
                        <input type="number" class="form-control" id="usuario_id" name="usuario_id" required
                            min="1" placeholder="Ej: 1">
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Comentario:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required placeholder="Escribe tu respuesta..."></textarea>
                    </div>
                    <input type="hidden" id="respuesta_id" name="respuesta_id" value="{{ $comentario->id }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-paper-plane"></i> Enviar Respuesta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
