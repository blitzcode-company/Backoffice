<div class="modal fade" id="sendEmailModal" tabindex="-1" role="dialog" aria-labelledby="sendEmailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendEmailModalLabel">Enviar Correo a ({{ $user->email }})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sendEmailForm" action="{{ route('correo.enviar') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="asunto">Asunto:</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" required>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                    </div>
                    <input type="hidden" class="form-control" id="destinatario" name="destinatario"
                        value="{{ $user->email }}">
                    <input type="hidden" name="ruta" value="{{ $ruta }}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
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
    document.querySelector('.btn-success').addEventListener('click', function(event) {
        event.preventDefault();
        $('#sendEmailModal').modal('show');
    });
</script>
