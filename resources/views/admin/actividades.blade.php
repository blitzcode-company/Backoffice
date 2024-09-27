@extends('layouts.app')

@section('content')
    <div class="titulo">Actividades de {{ $usuario->name }}</div>
    <div class="table-style">
        @if ($actividades->isEmpty())
            <p>No hay actividades para mostrar.</p>
        @else
            <table id="actividadesTable" class="table my-3">
                <thead>
                    <tr>
                        <th><i class="fas fa-tasks"></i> Actividad</th>
                        <th>Fecha y Hora</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($actividades as $actividad)
                        <tr>
                            <td data-bs-toggle="modal" data-bs-target="#activityModal{{ $actividad->id }}">
                                #{{ $actividad->id }} {{ $actividad->nombre }}
                            </td>
                            <td class="time-column">{{ $actividad->created_at->format('d/m/Y H:i') }} <i
                                    class="fas fa-history"></i></td>
                        </tr>
                        @include('modals.ActivityModal', [
                            'actividad' => $actividad,
                            'usuario' => $usuario,
                        ])
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#actividadesTable').DataTable({
                "language": {
                    "url": "{{ asset('js/lang/es-ES.json') }}"
                },
                "paging": true,
                "lengthChange": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "searching": true,
                "pageLength": 20,
                "responsive": true,
                "dom": 'Bfrtip',
                "buttons": [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pagingType": "simple_numbers"
            });
        });
    </script>
@endsection
