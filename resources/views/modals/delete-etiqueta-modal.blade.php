<div class="modal fade" id="deleteModal-{{ $etiqueta->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteModalLabel-{{ $etiqueta->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $etiqueta->id }}">
                    <i class="fas fa-trash-alt me-2"></i> Eliminar Etiqueta
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                <p>¿Estás seguro de que quieres eliminar esta etiqueta?</p>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('etiquetas.eliminar', ['id' => $etiqueta->id]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
