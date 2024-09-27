<div class="modal fade" id="confirmDeleteModal-{{ $canal->id }}" tabindex="-1" role="dialog"
    aria-labelledby="confirmDeleteModalLabel-{{ $canal->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel-{{ $canal->id }}">Confirmar Eliminación</h5>
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>
            <div class="modal-body">
                <p>¿Con qué motivo deseas eliminar el canal <strong>{{ $canal->nombre }}</strong>?</p><br>

                <select id="delete-reason-{{ $canal->id }}" class="form-control" required>
                    <option value="">Selecciona un motivo</option>
                    <option value="Violación de políticas generales">Violación de políticas generales</option>
                    <option value="Inactividad prolongada del canal">Inactividad prolongada del canal</option>
                    <option value="Contenido inapropiado">Contenido inapropiado</option>
                    <option value="Violación de derechos de autor">Violación de derechos de autor</option>
                </select>
  
                <p>Para confirmar la eliminación, ingresa el ID del canal en el siguiente campo:</p><br>
                <p><strong>ID: #{{ $canal->id }}</strong></p>
                <input type="text" id="confirm-id-{{ $canal->id }}" class="form-control"
                    placeholder="Ingresa el ID del canal" required>
            </div>
            <div class="modal-footer">
                <form action="{{ route('canal.eliminar', ['id' => $canal->id]) }}" method="POST"
                    id="delete-form-{{ $canal->id }}">
                    @csrf
                    @method('DELETE')


                    <input type="hidden" name="motivo" id="hidden-reason-{{ $canal->id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <button type="submit" class="btn btn-danger" id="delete-btn-{{ $canal->id }}" disabled>
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.modal').forEach(modal => {
            const input = modal.querySelector('input[type="text"]');
            const hiddenReason = modal.querySelector('input[name="motivo"]');
            const button = modal.querySelector('button[type="submit"]');
            const modalId = input.id.split('-')[2];
            const select = modal.querySelector('select');

            select.addEventListener('change', function() {
                hiddenReason.value = select.value;
                console.log('Motivo seleccionado: ' + hiddenReason.value);
                button.disabled = input.value !== modalId || select.value === '';
            });

            input.addEventListener('input', function() {
                button.disabled = input.value !== modalId || select.value === '';
            });
        });
    });
</script>
