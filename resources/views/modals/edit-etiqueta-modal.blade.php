<div class="modal fade" id="editModal-{{ $etiqueta->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel-{{ $etiqueta->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel-{{ $etiqueta->id }}">
                    <i class="fas fa-edit me-2"></i> Editar Etiqueta
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('etiquetas.editar', ['id' => $etiqueta->id]) }}" method="POST">
                <div class="modal-body text-start">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombre-{{ $etiqueta->id }}" class="form-label">Nombre de la Etiqueta:</label>
                        <input type="text" name="nombre" id="nombre-{{ $etiqueta->id }}" class="form-control"
                            value="{{ $etiqueta->nombre }}" required>
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
