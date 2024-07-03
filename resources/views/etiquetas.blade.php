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
                <h2>Lista de Etiquetas</h2>
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

                        <div class="modal fade" id="editModal-{{ $etiqueta->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="editModalLabel-{{ $etiqueta->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel-{{ $etiqueta->id }}">Editar Etiqueta</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('etiquetas.actualizar', ['id' => $etiqueta->id]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="nombre-{{ $etiqueta->id }}">Nombre de la Etiqueta:</label>
                                                <input type="text" name="nombre" id="nombre-{{ $etiqueta->id }}"
                                                    class="form-control" value="{{ $etiqueta->nombre }}" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteModal-{{ $etiqueta->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="deleteModalLabel-{{ $etiqueta->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $etiqueta->id }}">Eliminar Etiqueta
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Estás seguro de que quieres eliminar esta etiqueta?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('etiquetas.eliminar', ['id' => $etiqueta->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
