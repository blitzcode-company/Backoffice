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

    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 d-flex justify-content-center">
            <form action="{{ route('suscriptores.nombre', ['id' => $canal->id]) }}" method="GET" class="w-100" style="max-width: 600px;">
                <div class="input-group shadow-sm">
                    <input type="search" name="nombre" placeholder="Buscar suscriptor por nombre..." class="form-control search-bar border-end-0"
                        value="{{ request('nombre') }}" aria-label="Buscar suscriptor">
                    <button type="submit" class="btn btn-info border-start-0" style="max-width: 60px;">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>
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
                            @php
                                $rawChannels = $suscriptor->canales;
                                $channelsToDisplay = collect();
                                if ($rawChannels instanceof \Illuminate\Database\Eloquent\Collection) {
                                    $channelsToDisplay = $rawChannels->filter(function ($item) {
                                        return $item instanceof \App\Models\Blitzvideo\Canal;
                                    });
                                } 
                                elseif ($rawChannels instanceof \App\Models\Blitzvideo\Canal) {
                                    $channelsToDisplay->push($rawChannels);
                                }
                            @endphp

                            @if ($channelsToDisplay->isNotEmpty())
                                <div class="user-canales">
                                    @foreach ($channelsToDisplay as $canalSuscrito)
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