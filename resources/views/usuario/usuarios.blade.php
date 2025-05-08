@extends('layouts.app')

@section('content')
    <div class="titulo">Usuarios de Blitzvideo</div>
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
    <div class="d-flex justify-content-center">
        {{ $users->links('vendor.pagination.pagination') }}
    </div>
    <div class="user-list-container">
        @if ($users->isEmpty())
            <p>No hay usuarios disponibles.</p>
        @else
            <ul class="user-list">
                @foreach ($users as $user)
                    <li class="user-item">
                        <div class="user-photo">
                            <a href="{{ route('usuario.detalle', ['id' => $user->id]) }}">
                                <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}"
                                    alt="{{ $user->name }}">
                            </a>
                        </div>
                        <div class="user-info">
                            <h2 style="display: flex; align-items: center;">
                                <a href="{{ route('usuario.detalle', ['id' => $user->id]) }}">
                                    {{ $user->name }}
                                </a>
                            </h2>
                            <div class="email-container">
                                <p class="d-flex align-items-center">
                                    <span class="h4 m-0 button-separator">#{{ $user->id }}</span>
                                    <a href="{{ route('usuario.detalle', ['id' => $user->id]) }}" class="button-info"
                                        title="Ver Usuario">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <button class="btn btn-secondary btn-sm copy-btn" data-copy="{{ $user->id }}">
                                        <i class="fas fa-copy copy-icon"></i>
                                    </button>
                                    <span class="copy-status text-muted ml-2">Copiar</span>
                                </p>
                            </div>
                            <p>
                                <strong>Premium:</strong>
                                @if ($user->premium)
                                    <i class="fas fa-check" style="color: green;"></i>
                                @else
                                    <i class="fas fa-times" style="color: red;"></i>
                                @endif
                            
                                @if ($user->bloqueado)
                                <span style="color: red; margin-left: 10px;">
                                        <i class="fas fa-ban"></i> Bloqueado
                                    </span>
                                @endif
                            </p>

                            @if ($user->canal)
                                <div class="user-canal">
                                    <p>
                                        <a class="custom-link"
                                            href="{{ route('canal.detalle', ['id' => $user->canal->id]) }}">
                                            {{ $user->canal->nombre }}
                                            <i class="fas fa-link"></i>
                                        </a>
                                    </p>
                                </div>
                            @else
                                <p>No tiene canal asociado.</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="d-flex justify-content-center">
        {{ $users->links('vendor.pagination.pagination') }}
    </div>
    <script src="{{ asset('js/copyButton.js') }}"></script>
@endsection
