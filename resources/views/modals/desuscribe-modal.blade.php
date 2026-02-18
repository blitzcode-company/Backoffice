<div class="modal fade" id="modalDesuscribir{{ $suscriptor->id }}" tabindex="-1"
    aria-labelledby="modalLabel{{ $suscriptor->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{ $suscriptor->id }}">
                    <i class="fas fa-user-minus me-2"></i> Confirmar Desuscripción
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                    <p>¿Estás seguro de que deseas desuscribir a <strong>{{ $suscriptor->name }}</strong>?</p>
                </div>
            </div>
            <div class="modal-footer">
                <form
                    action="{{ route('suscriptores.desuscribir', ['canalId' => $canal->id, 'suscribeId' => $suscriptor->suscribe_id]) }}"
                    method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm btn-auto-width">
                        <i class="fas fa-user-minus"></i> Desuscribir
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
