<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">
                    <i class="fas fa-envelope me-2"></i> Enviar Correo a ({{ $user->email }})
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-start">
                <form id="sendEmailForm" action="{{ route('correo.enviar') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto:</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" required>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                    </div>
                    <input type="hidden" class="form-control" id="destinatario" name="destinatario"
                        value="{{ $user->email }}">
                    <input type="hidden" name="ruta" value="{{ $ruta }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <button type="submit" form="sendEmailForm" class="btn btn-success btn-sm">
                    <i class="fas fa-envelope"></i> Enviar Correo
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('sendEmailForm').addEventListener('submit', function(event) {
        const modal = document.getElementById('sendEmailModal');
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modal);
        modalInstance.hide();
    });

    $('#sendEmailModal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').remove();
    });
</script>
