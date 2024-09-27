<div class="modal fade" id="confirmBlockModal{{ $comentario->id }}" tabindex="-1"
    aria-labelledby="confirmBlockModalLabel{{ $comentario->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmBlockModalLabel{{ $comentario->id }}">
                    Confirmar {{ $comentario->bloqueado ? 'Desbloqueo' : 'Bloqueo' }}</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas {{ $comentario->bloqueado ? 'desbloquear' : 'bloquear' }} este
                    comentario?</p>
            </div>
            <div class="modal-footer">
                <form
                    action="{{ $comentario->bloqueado ? route('comentarios.desbloquear', $comentario->id) : route('comentarios.bloquear', $comentario->id) }}"
                    method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="btn {{ $comentario->bloqueado ? 'btn-secondary' : 'btn-warning' }} btn-sm">
                        <i class="fas fa-ban"></i> {{ $comentario->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
