<div class="modal fade" id="activityModal{{ $actividad->id }}" tabindex="-1"
    aria-labelledby="activityModalLabel{{ $actividad->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="activityModalLabel{{ $actividad->id }}">Detalles de la Actividad
                    #{{ $actividad->id }}</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close"> <span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="detalles-body">
                    <p>(<strong>{{ $usuario->username }}</strong>#{{ $usuario->id }}) | {{ $actividad->nombre }} a las
                        {{ $actividad->created_at->format('H:i') }} del día
                        {{ $actividad->created_at->format('d/m/Y') }}</p>
                    <p><strong>Datos relevantes:</strong></p>
                    <div style="text-align: left;">
                        @foreach (explode(';', $actividad->detalles) as $detalle)
                            <p class="m-0">{{ trim($detalle) }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
