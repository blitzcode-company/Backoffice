@extends('layouts.app')

@section('content')
    <div class="titulo">Playlists de Blitzvideo</div>
    <div class="search-container">
        <form action="{{ route('playlists.listar') }}" method="GET">
            @csrf
            <input type="search" name="nombre" placeholder="Buscar playlists por nombre" class="search-bar" required>
            <button type="submit" class="btn-info"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="text-center my-4">
        <a href="{{ route('playlists.crear.formulario') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva playlist
        </a>
    </div>
    <div class="d-flex justify-content-center">
        {{ $playlists->links('vendor.pagination.pagination') }}
    </div>
    <div class="playlist-container mx-auto">
        @if ($playlists->isEmpty())
            <p class="text-muted mx-auto">No hay playlists disponibles.</p>
        @else
        <div class="row d-flex justify-content-between">
            @foreach ($playlists as $playlist)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="video-title">{{ $playlist['nombre'] }}</h2>
                        </div>
                        <div class="card-body">
                            <p class="card-text text-start">
                                <i class="fas {{ $playlist['acceso'] == 1 ? 'fa-globe' : 'fa-lock' }} text-muted"></i>
                                <span class="text-muted">Acceso:</span>
                                <span class="{{ $playlist['acceso'] == 1 ? 'text-success' : 'text-danger' }}">
                                    {{ $playlist['acceso'] == 1 ? 'Público' : 'Privado' }}
                                </span>
                                <br>
                                <i class="fas fa-video text-muted"></i>
                                <span class="text-muted">Videos:</span> {{ $playlist['cantidad_videos'] }} <br>
                                <i class="fas fa-user text-muted"></i>
                                <span class="text-muted">Propietario:</span> {{ $playlist['propietario'] }}
                            </p>
                            <a href="{{ route('playlists.videos', ['id' => $playlist['id']]) }}" class="btn btn-primary">
                                Ver Videos
                            </a>
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
