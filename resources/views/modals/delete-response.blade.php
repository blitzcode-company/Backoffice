<div class="modal fade" id="confirmDeleteModalRespuesta{{ $respuesta->id }}" tabindex="-1"
    aria-labelledby="confirmDeleteModalLabelRespuesta{{ $respuesta->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabelRespuesta{{ $respuesta->id }}">Confirmar Eliminación
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar esta respuesta?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('comentarios.eliminar', ['comentario_id' => $respuesta->id]) }}" method="POST"
                    class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
