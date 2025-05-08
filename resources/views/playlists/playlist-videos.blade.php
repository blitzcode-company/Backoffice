@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>{{ $playlistData['nombre'] }}</span>
    </div>

    <div class="playlist-info mb-5 ml-4">
        <i class="fas fa-user text-muted"></i>
        <span class="text-muted">Propietario:</span>
        <a class="custom-link mb-2 " href="{{ route('usuario.detalle', ['id' => $playlistData['user_id']]) }}">
            {{ $playlistData['propietario'] }} <i class="fas fa-link"></i></a>
        </a>

        <div class="acceso-container">
            <p><span id="acceso-text" class="{{ $playlistData['acceso'] == 1 ? 'publico' : 'privado' }}">
                    <i class="{{ $playlistData['acceso'] == 1 ? 'fas fa-unlock' : 'fas fa-lock' }}"></i>
                    {{ $playlistData['acceso'] == 1 ? 'Público' : 'Privado' }}
                </span></p>
            <label class="switch">
                <input type="checkbox" id="acceso-switch" {{ $playlistData['acceso'] == 1 ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>
        <div class="mt-2">

            <button type="button" class="btn btn-link p-0 m-0 align-baseline text-decoration-none" data-bs-toggle="modal"
                data-bs-target="#editPlaylistModal-{{ $playlistData['id'] }}">
                <i class="fas fa-edit "></i> Editar
            </button>
            <span class="button-separator p-0 mx-2"></span>
            <button type="button" class="btn btn-link p-0 m-0 align-baseline text-decoration-none" data-bs-toggle="modal"
                data-bs-target="#confirmDeleteModal-{{ $playlistData['id'] }}">
                <i class="fas fa-trash-alt"></i> Eliminar
            </button>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success text-center mx-auto" style="max-width: 500px; margin-top: 0 !important;">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-center">
        {{ $videos->links('vendor.pagination.pagination') }}
    </div>

    @if ($videos->isEmpty())
        <p>No hay videos disponibles.</p>
    @else
        <div class="video-list-container">
            <div class="video-list">
                @foreach ($videos as $video)
                    <div class="card mb-3 video-card">
                        <div class="video-thumbnail position-relative">
                            <a href="{{ route('video.detalle', ['id' => $video->id]) }}">
                                @if ($video->miniatura)
                                    <img src="{{ $video->miniatura }}" alt="Miniatura del video">
                                @else
                                    <img src="{{ asset('img/video-default.png') }}" alt="Miniatura por defecto">
                                @endif
                            </a>
                            <div class="video-duration">
                                {{ floor($video->duracion / 60) }}:{{ str_pad($video->duracion % 60, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div class="video-info">
                            <h2 class="video-title">{{ $video->titulo }}</h2>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-center">
        {{ $videos->links('vendor.pagination.pagination') }}
    </div>
    @include('modals.delete-playlist', ['playlistData' => $playlistData])
    @include('modals.edit-playlist', ['playlistData' => $playlistData])
    <script>
        document.getElementById('acceso-switch').addEventListener('change', function() {
            const acceso = this.checked ? 1 : 0;
            const id = {{ $playlistData['id'] }};

            fetch(`/playlists/${id}/acceso`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        acceso
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const accesoText = document.getElementById('acceso-text');
                        accesoText.innerHTML = acceso ?
                            '<i class="fas fa-unlock"></i> Público' :
                            '<i class="fas fa-lock"></i> Privado';

                        accesoText.className = acceso ? 'publico' : 'privado';
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

@endsection
