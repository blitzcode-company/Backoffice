<div class="modal fade" id="editModal-{{ $etiqueta->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel-{{ $etiqueta->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel-{{ $etiqueta->id }}">Editar Etiqueta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('etiquetas.editar', ['id' => $etiqueta->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nombre-{{ $etiqueta->id }}">Nombre de la Etiqueta:</label>
                        <input type="text" name="nombre" id="nombre-{{ $etiqueta->id }}" class="form-control"
                            value="{{ $etiqueta->nombre }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
