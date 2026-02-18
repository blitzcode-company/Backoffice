<div class="modal fade" id="confirmDeleteModal-{{ $canal->id }}" tabindex="-1"
    aria-labelledby="confirmDeleteModalLabel-{{ $canal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel-{{ $canal->id }}">
                    <i class="fas fa-trash-alt me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p class="mb-2">¿Con qué motivo deseas eliminar el canal <strong>{{ $canal->nombre }}</strong>?</p>
                    <select id="delete-reason-{{ $canal->id }}" class="form-control" required>
                        <option value="">Selecciona un motivo</option>
                        <option value="Violación de políticas generales">Violación de políticas generales</option>
                        <option value="Inactividad prolongada del canal">Inactividad prolongada del canal</option>
                        <option value="Contenido inapropiado">Contenido inapropiado</option>
                        <option value="Violación de derechos de autor">Violación de derechos de autor</option>
                    </select>
                </div>

                <div class="mb-3">
                    <p class="mb-2">Para confirmar la eliminación, ingresa el ID del canal en el siguiente campo:</p>
                    <p class="mb-2 text-primary font-weight-bold">ID: #{{ $canal->id }}</p>
                    <input type="text" id="confirm-id-{{ $canal->id }}" class="form-control"
                        placeholder="Ingresa el ID del canal" required>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('canal.eliminar', ['id' => $canal->id]) }}" method="POST"
                    id="delete-form-{{ $canal->id }}" class="d-inline">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="motivo" id="hidden-reason-{{ $canal->id }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <button type="submit" class="btn btn-danger btn-sm" id="delete-btn-{{ $canal->id }}" disabled>
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('confirmDeleteModal-{{ $canal->id }}');
        if (modal) {
            const input = document.getElementById('confirm-id-{{ $canal->id }}');
            const hiddenReason = document.getElementById('hidden-reason-{{ $canal->id }}');
            const button = document.getElementById('delete-btn-{{ $canal->id }}');
            const select = document.getElementById('delete-reason-{{ $canal->id }}');
            const targetId = '{{ $canal->id }}';

            const validate = () => {
                hiddenReason.value = select.value;
                button.disabled = input.value !== targetId || select.value === '';
            };

            select.addEventListener('change', validate);
            input.addEventListener('input', validate);
        }
    });
</script>
