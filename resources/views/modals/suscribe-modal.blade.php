<div class="modal fade" id="suscribirModal" tabindex="-1" aria-labelledby="suscribirModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suscribirModalLabel">Suscribir Usuario</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                    aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                Ingresa ID de usuario que deseas suscribir a <strong>{{ $canal->nombre }}</strong>
            </div>
            <form action="{{ route('suscriptores.suscribir', ['canalId' => $canal->id]) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="text" class="form-control w-50" id="usuario_id" name="usuario_id"
                        placeholder="Ingrese el ID del usuario" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Suscribir</button>
                </div>
            </form>
        </div>
    </div>
</div>
