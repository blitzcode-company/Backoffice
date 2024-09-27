<div class="modal fade" id="deleteModal-{{ $etiqueta->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteModalLabel-{{ $etiqueta->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $etiqueta->id }}">Eliminar Etiqueta</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres eliminar esta etiqueta?</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('etiquetas.eliminar', ['id' => $etiqueta->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
