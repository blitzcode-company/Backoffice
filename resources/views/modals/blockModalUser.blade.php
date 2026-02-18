<div class="modal fade" id="confirmBlockModalUser{{ $user->id }}" tabindex="-1"
    aria-labelledby="confirmBlockModalLabelUser{{ $user->id }}" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="confirmBlockModalLabelUser{{ $user->id }}">
                   <i class="fas {{ $user->bloqueado ? 'fa-user-check' : 'fa-user-slash' }} me-2"></i>
                   Confirmar {{ $user->bloqueado ? 'Desbloqueo' : 'Bloqueo' }}
               </h5>
               <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body">
               @if (!$user->bloqueado)
                   <div class="mb-3">
                       <p class="mb-2">¿Con qué motivo deseas bloquear este usuario?</p>
                   <select id="block-reason-{{ $user->id }}" class="form-control" required>
                       <option value="">Selecciona un motivo</option>
                       <option value="Conducta inapropiada">Conducta inapropiada</option>
                       <option value="Incumplimiento de políticas">Incumplimiento de políticas</option>
                       <option value="Petición de usuario">Petición de usuario</option>
                       <option value="Otro">Otro</option>
                   </select>
                   </div>
               @endif
               <div class="mb-3">
                   <p class="mb-2">Para confirmar, ingresa el ID del usuario:</p>
                   <p class="mb-2 text-primary font-weight-bold">ID: #{{ $user->id }}</p>
                   <input type="text" id="confirm-id-user-{{ $user->id }}" class="form-control"
                          placeholder="Ingresa el ID del usuario" required>
               </div>
           </div>
           <div class="modal-footer">
               <form action="{{ $user->bloqueado ? route('usuario.desbloquear', $user->id) : route('usuario.bloquear', $user->id) }}"
                     method="POST" class="d-inline">
                   @csrf
                   @method('POST')
                   <input type="hidden" name="motivo" id="hidden-reason-user-{{ $user->id }}">
                   
                   <button type="submit" class="btn {{ $user->bloqueado ? 'btn-success' : 'btn-warning' }} btn-sm"
                           id="block-btn-{{ $user->id }}" disabled>
                       <i class="fas {{ $user->bloqueado ? 'fa-unlock' : 'fa-ban' }}"></i> {{ $user->bloqueado ? 'Desbloquear' : 'Bloquear' }}
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
        const modalId = '{{ $user->id }}';
        const select = document.getElementById(`block-reason-${modalId}`);
        const input = document.getElementById(`confirm-id-user-${modalId}`);
        const hiddenReason = document.getElementById(`hidden-reason-user-${modalId}`);
        const button = document.getElementById(`block-btn-${modalId}`);
        const isUnblocking = {{ json_encode($user->bloqueado) }};
        
        function toggleButtonState() {
            const isIdCorrect = input.value.trim() === modalId;
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
</script>
