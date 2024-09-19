@extends('layouts.app')

@section('content')
    <div class="container-etiquetas">
        <div class="grid-container">
            <div class="form-column-etiqueta">
                <form action="{{ route('etiquetas.crear') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nombre">Nombre de la Etiqueta:</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Etiqueta</button>
                </form>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="list-column">
                <h2>Lista de Etiquetas (Total: {{ $etiquetas->count() }})</h2>
                <ul>
                    @foreach ($etiquetas as $etiqueta)
                        <li class="etiqueta-item">
                            <span class="etiqueta-text">ID: {{ $etiqueta->id }} - {{ $etiqueta->nombre }}</span>
                            <div class="etiqueta-actions">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                    data-target="#editModal-{{ $etiqueta->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#deleteModal-{{ $etiqueta->id }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </li>
                        @include('modals.edit-modal', ['etiqueta' => $etiqueta])
                        @include('modals.delete-modal', ['etiqueta' => $etiqueta])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
