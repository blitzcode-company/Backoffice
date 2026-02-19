@extends('layouts.app')

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm me-3 rounded-circle shadow-sm" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="titulo mb-0 border-0 p-0" style="font-size: 1.75rem;">Suscriptores del Canal: {{ $canal->nombre }}</h2>
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

    <div class="d-flex justify-content-center mb-4">
        {{ $suscriptores->links('vendor.pagination.pagination') }}
    </div>

    <div class="subscriber-list-container">
        @if ($suscriptores->isEmpty())
            <div class="alert alert-info text-center mx-auto shadow-sm" style="max-width: 600px;">
                <i class="fas fa-info-circle me-2"></i> No hay suscriptores para este canal.
            </div>
        @else
            <div class="subscriber-list">
                @foreach ($suscriptores as $suscriptor)
                    <div class="subscriber-item">
                        <div class="subscriber-avatar-wrapper">
                            <a href="{{ route('usuario.detalle', ['id' => $suscriptor->id]) }}">
                                <img src="{{ $suscriptor->foto ? asset($suscriptor->foto) : asset('img/default-user.png') }}"
                                    alt="{{ $suscriptor->name }}" class="subscriber-avatar">
                            </a>
                        </div>
                        
                        <div class="subscriber-details">
                            <div class="d-flex align-items-center flex-wrap mb-1 justify-content-center justify-content-md-start">
                                <h3 class="subscriber-name me-2">
                                    <a href="{{ route('usuario.detalle', ['id' => $suscriptor->id]) }}">
                                        {{ $suscriptor->name }}
                                    </a>
                                </h3>
                                @if ($suscriptor->premium)
                                    <span class="badge bg-warning text-dark rounded-pill" title="Usuario Premium">
                                        <i class="fas fa-crown text-dark" style="font-size: 0.8em;"></i> Premium
                                    </span>
                                @endif
                            </div>
                            
                            <div class="subscriber-meta justify-content-center justify-content-md-start">
                                <span title="ID de Usuario"><i class="fas fa-hashtag text-muted small me-1"></i>{{ $suscriptor->id }}</span>
                                <span class="text-muted d-none d-md-inline">|</span>
                                <span class="text-truncate" style="max-width: 250px;" title="{{ $suscriptor->email }}">
                                    <i class="fas fa-envelope text-muted small me-1"></i>{{ $suscriptor->email }}
                                </span>
                            </div>

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
                                <div class="subscriber-channels justify-content-center justify-content-md-start">
                                    <small class="text-muted me-1">Canales:</small>
                                    @foreach ($channelsToDisplay as $canalSuscrito)
                                        <a href="{{ route('canal.detalle', ['id' => $canalSuscrito->id]) }}" 
                                           class="badge bg-light text-dark border text-decoration-none fw-normal">
                                            {{ $canalSuscrito->nombre }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="subscriber-actions">
                            <div class="d-flex flex-column align-items-center me-2">
                                <button class="btn btn-outline-secondary btn-sm copy-btn rounded-circle" data-copy="{{ $suscriptor->id }}" title="Copiar ID" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <span class="copy-status small text-muted mt-1" style="font-size: 0.7rem;">Copiar</span>
                            </div>
                            
                            <a href="{{ route('usuario.detalle', ['id' => $suscriptor->id]) }}"
                                class="btn btn-outline-primary btn-sm" title="Ver Perfil">
                                <i class="fas fa-eye me-1"></i> Ver
                            </a>
                            
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalDesuscribir{{ $suscriptor->id }}" title="Desuscribir">
                                <i class="fas fa-user-minus me-1"></i> Desuscribir
                            </button>
                        </div>
                    </div>
                    @include('modals.desuscribe-modal', ['suscriptor' => $suscriptor])
                @endforeach
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $suscriptores->links('vendor.pagination.pagination') }}
    </div>

    <script src="{{ asset('js/copyButton.js') }}"></script>
@endsection