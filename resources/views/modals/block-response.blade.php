<div class="modal fade" id="confirmBlockModalRespuesta{{ $respuesta->id }}" tabindex="-1"
    aria-labelledby="confirmBlockModalLabelRespuesta{{ $respuesta->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmBlockModalLabelRespuesta{{ $respuesta->id }}">
                    Confirmar {{ $respuesta->bloqueado ? 'Desbloqueo' : 'Bloqueo' }}</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas {{ $respuesta->bloqueado ? 'desbloquear' : 'bloquear' }} esta respuesta?
                </p>
            </div>
            <div class="modal-footer">
                <form
                    action="{{ $respuesta->bloqueado ? route('comentarios.desbloquear', $respuesta->id) : route('comentarios.bloquear', $respuesta->id) }}"
                    method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="btn {{ $respuesta->bloqueado ? 'btn-secondary' : 'btn-warning' }} btn-sm">
                        <i class="fas fa-ban"></i> {{ $respuesta->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
