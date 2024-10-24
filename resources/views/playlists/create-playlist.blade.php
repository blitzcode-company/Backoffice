@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Crear Nueva Playlist</span>
    </div>
    <div class="playlist-container mt-4">
        <div class="playlist-form-container">
            <form id="playlistForm" method="POST" action="{{ route('playlists.crear') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" class="playlist-input" id="nombre" name="nombre"
                        placeholder="Ingresa el nombre" required>
                </div>

                <div class="mb-3">
                    <select class="playlist-select" id="acceso" name="acceso" required>
                        <option value="1"> Acceso Público</option>
                        <option value="0">Acceso Privado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <input type="number" class="playlist-input" id="user_id" name="user_id"
                        placeholder="Ingresa el ID del usuario" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="playlist-input" id="search" placeholder="Buscar videos por título..."
                        onkeyup="buscarVideos()">
                    <ul class="playlist-list-group video-list-select" id="searchResults"></ul>
                </div>

                <input type="hidden" name="videos" id="videos" value="">

                <button type="submit" class="btn btn-primary margin1">Crear Playlist</button>
            </form>

            <div id="responseMessage" class="playlist-response-message mt-3"></div>
            @if (session('success'))
                <div class="alert alert-success mt-3 text-center">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger mt-3 text-center">{{ session('error') }}</div>
            @endif

        </div>
        <div class="playlist-videos-container">
            <h5 class="text-muted">Playlist</h5>
            <hr class="m-0">
            <ul class="playlist-list-group" id="selectedVideos"></ul>
        </div>
    </div>

    <script src="{{ asset('js/playlist.js') }}"></script>
@endsection
