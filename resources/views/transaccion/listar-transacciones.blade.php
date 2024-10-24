@extends('layouts.app')

@section('content')
    <div class="titulo">Registro de Pagos</div>
    <div class="container-transaccion">
        <form action="{{ route('transaccion.filtrar') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="plan" class="form-control" placeholder="Nombre del Plan"
                        value="{{ request('plan') }}">
                </div>
                <div class="col-md-4">
                    <input type="number" name="user_id" class="form-control" placeholder="ID del Usuario"
                        value="{{ request('user_id') }}">
                </div>
                <div class="col-md-4">
                    <input type="number" name="id" class="form-control" placeholder="ID del Transaccion"
                        value="{{ request('id') }}">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <select name="estado" class="form-control">
                        <option value="">-- Estado --</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>
        
        @if ($transaccion->isEmpty())
            <p>No se encontraron planes.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Método de Pago</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Cancelación</th>
                            <th>ID de Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaccion as $t)
                            <tr>
                                <td>{{ $t->id }}</td>
                                <td>{{ $t->plan }}</td>
                                <td>{{ $t->metodo_de_pago }}</td>
                                <td>{{ $t->fecha_inicio }}</td>
                                <td>{{ $t->fecha_cancelacion ?? 'Activo' }}</td>
                                <td>
                                    <a href="{{ route('usuario.detalle', $t->user_id) }}">
                                        {{ $t->user_id }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
