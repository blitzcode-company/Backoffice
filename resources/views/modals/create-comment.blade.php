<div class="modal fade" id="createCommentModal" tabindex="-1" role="dialog"
    aria-labelledby="createCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCommentModalLabel">
                    <i class="fas fa-comment-medical me-2"></i> Nuevo Comentario
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('comentarios.crear') }}" method="POST">
                <div class="modal-body text-start">
                    @csrf
                    <input type="hidden" name="video_id" value="{{ $video->id }}">
                    <div class="mb-3">
                        <label for="usuario_id" class="form-label">ID del Usuario:</label>
                        <input type="number" class="form-control" id="usuario_id" name="usuario_id" required placeholder="Ej: 1">
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Comentario:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required placeholder="Escribe el comentario..."></textarea>
                    </div>
                    <input type="hidden" id="respuesta_id" name="respuesta_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
