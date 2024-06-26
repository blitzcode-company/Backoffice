@extends('layouts.app')

@section('content')
    <h1>Administración de Usuarios</h1>
    <div class="search-container">
        <form action="{{ route('usuarios-nombre') }}" method="POST">
        @csrf
            <input type="search" name="nombre" placeholder="Buscar usuario por nombre" class="search-bar" required>
            <button type="submit" class="btn-info"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="user-list-container">
        @if($users->isEmpty())
            <p>No hay usuarios disponibles.</p>
        @else
            <ul class="user-list">
                @foreach($users as $user)
                    <li class="user-item">
                        <div class="user-photo">
                            <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}" alt="{{ $user->name }}">
                        </div>
                        <div class="user-info">
                            <h2>{{ $user->name }}</h2>
                            <p>Email: {{ $user->email }}</p>
                            <p>Premium: {{ $user->premium ? 'Sí' : 'No' }}</p>
                            @if($user->canal)
                                <p>Canal: {{ $user->canal->nombre }}</p>
                            @else
                                <p>No tiene canal asociado.</p>
                            @endif
                        </div>
                        <div class="user-actions">
                            <a href="{{ route('usuario', ['id' => $user->id]) }}" class="btn-info">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
