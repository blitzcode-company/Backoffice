<div class="modal fade" id="confirmBlockModalVideo{{ $video->id }}" tabindex="-1"
    aria-labelledby="confirmBlockModalLabelVideo{{ $video->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmBlockModalLabelVideo{{ $video->id }}">
                    Confirmar {{ $video->bloqueado ? 'Desbloqueo' : 'Bloqueo' }}
                </h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p> {{ $video->bloqueado ? null : ' ¿Con qué motivo deseas bloquear este video?' }}</p>

                <select id="block-reason-{{ $video->id }}" class="form-control"
                    {{ $video->bloqueado ? 'disabled style=display:none' : 'required' }}>
                    <option value="">Selecciona un motivo</option>
                    <option value="Contenido inapropiado">Contenido inapropiado</option>
                    <option value="Incumplimiento de políticas">Incumplimiento de políticas</option>
                    <option value="Petición de usuario">Petición de usuario</option>
                    <option value="Otro">Otro</option>
                </select>

                <p>Para confirmar, ingresa el ID del video:</p>
                <p><strong>ID: #{{ $video->id }}</strong></p>
                <input type="text" id="confirm-id-video-{{ $video->id }}" class="form-control"
                    placeholder="Ingresa el ID del video" required>
            </div>
            <div class="modal-footer">
                <form
                    action="{{ $video->bloqueado ? route('video.desbloquear', $video->id) : route('video.bloquear', $video->id) }}"
                    method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="motivo" id="hidden-reason-video-{{ $video->id }}">

                    <button type="submit" class="btn {{ $video->bloqueado ? 'btn-secondary' : 'btn-warning' }} btn-sm"
                        id="block-btn-{{ $video->id }}" disabled>
                        <i class="fas fa-ban"></i> {{ $video->bloqueado ? 'Desbloquear' : 'Bloquear' }}
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
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            const videoIdMatch = modal.id.match(/confirmBlockModalVideo(\d+)/);
            if (!videoIdMatch) {
                console.warn('No se encontró el ID del video en el modal:', modal.id);
                return;
            }
            const videoId = videoIdMatch[1];
            const select = document.getElementById(`block-reason-${videoId}`);
            const input = document.getElementById(`confirm-id-video-${videoId}`);
            const hiddenReason = document.getElementById(`hidden-reason-video-${videoId}`);
            const button = document.getElementById(`block-btn-${videoId}`);

            const isUnblocking = {{ json_encode($video->bloqueado) }};

            function toggleButtonState() {
                const isIdCorrect = input.value.trim() === videoId;
                const isReasonSelected = select && select.value.trim() !== '';

                button.disabled = isUnblocking ? !isIdCorrect : !(isIdCorrect && isReasonSelected);
            }

            if (select) {
                select.addEventListener('change', function() {
                    hiddenReason.value = select.value;
                    toggleButtonState();
                });
            }

            input.addEventListener('keyup', function() {
                toggleButtonState();
            });
        });
    });
</script>
