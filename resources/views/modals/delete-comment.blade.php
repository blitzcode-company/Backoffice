 <div class="modal fade" id="confirmDeleteModal{{ $comentario->id }}" tabindex="-1" role="dialog"
     aria-labelledby="confirmDeleteModalLabel{{ $comentario->id }}" aria-hidden="true">
     <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="confirmDeleteModalLabel{{ $comentario->id }}">Confirmar
                     Eliminación</h5>
                 <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <p>¿Estás seguro de que deseas eliminar este comentario?</p>
             </div>
             <div class="modal-footer">
                 <form action="{{ route('comentarios.eliminar', ['comentario_id' => $comentario->id]) }}" method="POST"
                     class="d-inline">
                     @csrf
                     @method('DELETE')
                     <button type="submit" class="btn btn-danger btn-sm">
                         <i class="fas fa-trash"></i> Eliminar
                     </button>
                     <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                         <i class="fas fa-times"></i> Cancelar
                     </button>
                 </form>
             </div>
         </div>
     </div>
 </div>
