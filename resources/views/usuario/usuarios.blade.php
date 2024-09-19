@extends('layouts.app')

@section('content')
    <div class="search-container">
        <form action="{{ route('usuario.nombre') }}" method="GET">
            <input type="search" name="nombre" placeholder="Buscar usuario por nombre" class="search-bar"
                value="{{ request('nombre') }}" required>
            <button type="submit" class="btn-info"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="create-user-button-container text-center my-4">
        <a href="{{ route('usuario.crear.formulario') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>
    @if (session('success'))
        <div class="alert alert-success mx-auto w-50 my-0 mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif
    <div class="user-list-container">
        @if ($users->isEmpty())
            <p>No hay usuarios disponibles.</p>
        @else
            <ul class="user-list">
                @foreach ($users as $user)
                    <li class="user-item">
                        <div class="user-photo">
                            <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}"
                                alt="{{ $user->name }}">
                        </div>
                        <div class="user-info">
                            <h2>{{ $user->name }}</h2>
                            <p>Email: {{ $user->email }}</p>
                            <p>Premium: {{ $user->premium ? 'Sí' : 'No' }}</p>
                            @if ($user->canales->isNotEmpty())
                                <div class="user-canales">
                                    @foreach ($user->canales as $canal)
                                        <p>
                                            Canal: 
                                            <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}">
                                                {{ $canal->nombre }}
                                                <i class="fas fa-link"></i>
                                            </a>
                                        </p>
                                    @endforeach
                                </div>
                            @else
                                <p>No tiene canal asociado.</p>
                            @endif
                        </div>
                        <div class="user-actions">
                            <a href="{{ route('usuario.detalle', ['id' => $user->id]) }}" class="btn-info"
                                style="margin-right: 50px !important;">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
