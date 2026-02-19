@extends('layouts.app')

@section('content')
    <div class="titulo">Registro de Pagos</div>
    <div class="container-transaccion">
    
        <div class="transaction-filter-card">
            <div class="card-header-custom">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </div>
            <form action="{{ route('transaccion.filtrar') }}" method="GET">
                <div class="filter-grid">
                    <div class="form-group">
                        <label for="plan"><i class="fas fa-tag"></i> Nombre del Plan</label>
                        <input type="text" name="plan" id="plan" class="form-control w-100" placeholder="Ej. Premium"
                            value="{{ request('plan') }}">
                    </div>
                    <div class="form-group">
                        <label for="user_id"><i class="fas fa-user"></i> ID del Usuario</label>
                        <input type="number" name="user_id" id="user_id" class="form-control w-100" placeholder="Ej. 123"
                            value="{{ request('user_id') }}">
                    </div>
                    <div class="form-group">
                        <label for="id"><i class="fas fa-hashtag"></i> ID Transacción</label>
                        <input type="number" name="id" id="id" class="form-control w-100" placeholder="Ej. 999"
                            value="{{ request('id') }}">
                    </div>
                    <div class="form-group">
                        <label for="estado"><i class="fas fa-info-circle"></i> Estado</label>
                        <select name="estado" id="estado" class="form-select form-control w-100">
                            <option value="">-- Todos --</option>
                            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <a href="{{ request()->url() }}" class="btn btn-secondary me-2">
                        <i class="fas fa-eraser"></i> Limpiar
                    </a>
                    <button type="submit" formaction="{{ route('transaccion.exportar') }}" class="btn btn-success me-2">
                        <i class="fas fa-file-csv"></i> Exportar CSV
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        @if ($transaccion->isEmpty())
            <div class="empty-state">
                <i class="fas fa-file-invoice-dollar fa-3x"></i>
                <p>No se encontraron transacciones con los criterios seleccionados.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tabla-transacciones">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Plan</th>
                            <th>Método de Pago</th>
                            <th>Fecha de Inicio</th>
                            <th>Estado</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaccion as $t)
                            <tr>
                                <td><span class="badge bg-secondary">#{{ $t->id }}</span></td>
                                <td class="fw-bold">{{ $t->plan }}</td>
                                <td>
                                    <i class="fas fa-credit-card text-muted me-1"></i> {{ $t->metodo_de_pago }}
                                </td>
                                <td>{{ $t->fecha_inicio }}</td>
                                <td>
                                    @if($t->fecha_cancelacion)
                                        <span class="badge bg-danger">Cancelado</span>
                                        <div class="small text-muted mt-1" style="font-size: 0.8em;">{{ $t->fecha_cancelacion }}</div>
                                    @else
                                        <span class="badge bg-success">Activo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('usuario.detalle', $t->user_id) }}" class="user-link">
                                        <i class="fas fa-user-circle"></i> {{ $t->user_id }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(method_exists($transaccion, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $transaccion->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
