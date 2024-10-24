@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Suscriptores del Canal: {{ $canal->nombre }}</span>
    </div>
    <div class="search-container mb-5">
        <form action="{{ route('suscriptores.nombre', ['id' => $canal->id]) }}" method="GET">
            <input type="search" name="nombre" placeholder="Buscar suscriptor por nombre" class="search-bar"
                value="{{ request('nombre') }}">
            <button type="submit" class="btn-info"><i class="fas fa-search"></i></button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success text-center m-0 mx-auto mb-2" style="max-width: 500px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-center">
        {{ $suscriptores->links('vendor.pagination.pagination') }}
    </div>

    <div class="user-list-container">
        @if ($suscriptores->isEmpty())
            <p>No hay suscriptores para este canal.</p>
        @else
            <ul class="user-list">
                @foreach ($suscriptores as $suscriptor)
                    <li class="user-item">
                        <div class="user-photo">
                            <a href="{{ route('usuario.detalle', ['id' => $suscriptor->id]) }}">
                                <img src="{{ $suscriptor->foto ? asset($suscriptor->foto) : asset('img/default-user.png') }}"
                                    alt="{{ $suscriptor->name }}">
                            </a>
                        </div>
                        <div class="user-info">
                            <h2 style="display: flex; align-items: center;">
                                <a href="{{ route('usuario.detalle', ['id' => $suscriptor->id]) }}">
                                    {{ $suscriptor->name }}
                                </a>
                            </h2>

                            <div class="email-container">
                                <p class="d-flex align-items-center">
                                    <span class="h4 m-0 button-separator">#{{ $suscriptor->id }}</span>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalDesuscribir{{ $suscriptor->id }}">
                                        <i class="fas fa-user-times"></i> Desuscribir
                                    </button>
                                    <a href="{{ route('usuario.detalle', ['id' => $suscriptor->id]) }}"
                                        class="button-info mx-0" title="Ver Usuario">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <button class="btn btn-secondary btn-sm copy-btn" data-copy="{{ $suscriptor->id }}">
                                        <i class="fas fa-copy copy-icon"></i>
                                    </button>
                                    <span class="copy-status text-muted ml-2">Copiar</span>

                                </p>

                            </div>
                            <p><strong>Premium:</strong>
                                @if ($suscriptor->premium)
                                    <i class="fas fa-check" style="color: green;"></i>
                                @else
                                    <i class="fas fa-times" style="color: red;"></i>
                                @endif
                            </p>

                            @if ($suscriptor->canales->isNotEmpty())
                                <div class="user-canales">
                                    @foreach ($suscriptor->canales as $canalSuscrito)
                                        <p>
                                            <a class="custom-link"
                                                href="{{ route('canal.detalle', ['id' => $canalSuscrito->id]) }}">
                                                {{ $canalSuscrito->nombre }}
                                                <i class="fas fa-link"></i>
                                            </a>
                                        </p>
                                    @endforeach
                                </div>
                            @else
                                <p>No tiene canal asociado.</p>
                            @endif


                            @include('modals.desuscribe-modal', ['suscriptor' => $suscriptor])
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="d-flex justify-content-center">
        {{ $suscriptores->links('vendor.pagination.pagination') }}
    </div>

    <script src="{{ asset('js/copyButton.js') }}"></script>
@endsection
