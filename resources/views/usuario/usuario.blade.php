@extends('layouts.app')

@section('content')
    <h1>Información de Usuario</h1>

    <div class="user-details-container">
        <div class="user-photo-large">
        <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}" alt="{{ $user->name }}" >
        </div>
        <div class="user-info-box">
            <div class="user-info">
                <h2>{{ $user->name }}</h2>
                <p>Email: {{ $user->email }}</p>
                <p>Premium: {{ $user->premium ? 'Sí' : 'No' }}</p>
                @if($user->canal)
                    <p>Canal: {{ $user->canal->nombre }}</p>
                    <p>Descripción: {{ $user->canal->descripcion }}</p>
                @else
                    <p>No tiene canal asociado.</p>
                @endif
            </div>

            <div class="action-buttons">
                <a href="#" class="btn-action">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="#" class="btn-action">
                    <i class="fas fa-trash-alt"></i> Eliminar
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
@endsection
