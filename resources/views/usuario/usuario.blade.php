@extends('layouts.app')

@section('content')
    <h1>Información de Usuario</h1>

    <div class="user-details-container">
        <div class="user-photo-large">
            <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}" alt="{{ $user->name }}">
        </div>
        <div class="user-info-box">
            <div class="user-info">
                <h2>{{ $user->name }}</h2>
                <p>Email: {{ $user->email }}</p>
                <p>Premium: {{ $user->premium ? 'Sí' : 'No' }}</p>
                @if ($user->canal)
                    <p>Canal: {{ $user->canal->nombre }}</p>
                    <p>Descripción: {{ $user->canal->descripcion }}</p>
                @else
                    <p>No tiene canal asociado.</p>
                @endif
            </div>

            <div class="action-buttons">
                <a href="#" class="btn-action" data-toggle="modal" data-target="#confirmDeleteModal">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
                <a href="{{ route('update.usuario', ['id' => $user->id]) }}" class="btn-action">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="#" class="btn-action">
                    <i class="fas fa-envelope"></i> Enviar correo
                </a>
            </div>
        </div>
    </div>

    <div class="back-button-container">
        <a href="{{ route('usuarios') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver a Usuarios
        </a>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="modal-close">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que quieres eliminar este usuario?</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('eliminar.usuario', ['id' => $user->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </form>
                    <button type="button" class="btn btn-primary btn-no" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>


@endsection
