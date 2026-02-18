<div class="modal fade" id="suscribirModal" tabindex="-1" aria-labelledby="suscribirModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suscribirModalLabel">
                    <i class="fas fa-user-plus me-2"></i> Suscribir Usuario
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('suscriptores.suscribir', ['canalId' => $canal->id]) }}" method="POST">
                <div class="modal-body">
                    <p class="mb-3">Ingresa ID de usuario que deseas suscribir a <strong>{{ $canal->nombre }}</strong></p>
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control" id="usuario_id" name="usuario_id"
                            placeholder="Ingrese el ID del usuario" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm btn-auto-width">
                        <i class="fas fa-user-plus"></i> Suscribir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
