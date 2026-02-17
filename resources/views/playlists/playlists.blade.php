@extends('layouts.app')

@section('content')
    <div class="titulo">Playlists de Blitzvideo</div>

    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-3 d-none d-md-block"></div>
        
        <div class="col-12 col-md-6 d-flex justify-content-center">
            <form action="{{ route('playlists.listar') }}" method="GET" class="w-100" style="max-width: 600px;">
                <div class="input-group shadow-sm">
                    <input type="search" name="nombre" placeholder="Buscar playlists por nombre..." class="form-control search-bar border-end-0"
                        value="{{ request('nombre') }}" required aria-label="Buscar playlist">
                    <button type="submit" class="btn btn-info border-start-0" style="max-width: 60px;">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="col-12 col-md-3 d-flex justify-content-center">
            <a href="{{ route('playlists.crear.formulario') }}" class="btn btn-primary shadow-sm d-flex align-items-center gap-2" style="width: auto; padding: 10px 20px;">
                <i class="fas fa-plus"></i> <span>Nueva Playlist</span>
            </a>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $playlists->links('vendor.pagination.pagination') }}
    </div>
    <div class="playlist-container mx-auto">
        @if ($playlists->isEmpty())
            <p class="text-muted mx-auto text-center">No hay playlists disponibles.</p>
        @else
        <div class="row">
            @foreach ($playlists as $playlist)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-transparent border-bottom-0 pt-3">
                            <h5 class="card-title text-center fw-bold text-truncate mb-0" title="{{ $playlist['nombre'] }}">{{ $playlist['nombre'] }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="card-text text-start">
                                <div class="mb-2">
                                    <i class="fas {{ $playlist['acceso'] == 1 ? 'fa-globe' : 'fa-lock' }} text-muted me-2"></i>
                                    <span class="text-muted">Acceso:</span>
                                    <span class="badge {{ $playlist['acceso'] == 1 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                        {{ $playlist['acceso'] == 1 ? 'Público' : 'Privado' }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-video text-muted me-2"></i>
                                    <span class="text-muted">Videos:</span> {{ $playlist['cantidad_videos'] }}
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-user text-muted me-2"></i>
                                    <span class="text-muted">Propietario:</span> {{ $playlist['propietario'] }}
                                </div>
                                <a href="{{ route('playlists.videos', ['id' => $playlist['id']]) }}" class="btn btn-outline-primary w-100">
                                    Ver Videos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
    <div class="d-flex justify-content-center">
        {{ $playlists->links('vendor.pagination.pagination') }}
    </div>
@endsection
