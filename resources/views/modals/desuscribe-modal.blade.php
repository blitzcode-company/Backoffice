<div class="modal fade" id="modalDesuscribir{{ $suscriptor->id }}" tabindex="-1"
    aria-labelledby="modalLabel{{ $suscriptor->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel{{ $suscriptor->id }}">Confirmar Desuscripción</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas desuscribir a <strong>{{ $suscriptor->name }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form
                    action="{{ route('suscriptores.desuscribir', ['canalId' => $canal->id, 'suscribeId' => $suscriptor->suscribe_id]) }}"
                    method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Desuscribir</button>
                </form>
            </div>
        </div>
    </div>
</div>
