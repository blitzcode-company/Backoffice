<div class="modal fade" id="activityModal{{ $actividad->id }}" tabindex="-1"
    aria-labelledby="activityModalLabel{{ $actividad->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalLabel{{ $actividad->id }}">Detalles de la Actividad
                    #{{ $actividad->id }}</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="detalles-body text-start">
                    <div class="p-3 border rounded mb-3">
                        <p class="mb-1"><strong>Usuario:</strong> {{ $usuario->username }} <small class="text-muted">(ID: {{ $usuario->id }})</small></p>
                        <p class="mb-1"><strong>Acción:</strong> {{ $actividad->nombre }}</p>
                        <p class="mb-0"><strong>Fecha:</strong> {{ $actividad->created_at->format('d/m/Y') }} a las {{ $actividad->created_at->format('H:i') }}</p>
                    </div>
                    <p class="fw-bold border-bottom pb-2 mb-3">Datos relevantes:</p>
                    <div class="ps-2">
                        @foreach (explode(';', $actividad->detalles) as $detalle)
                            @if(trim($detalle))
                                <p class="mb-1"><i class="fas fa-angle-right text-primary me-2"></i>{{ trim($detalle) }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
