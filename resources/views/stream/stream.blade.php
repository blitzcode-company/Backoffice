@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Ver Transmisión en Vivo</span>
    </div>
    <div class="video-page-container">

        <div class="video-player-container">
            <video id="video" controls class="video-player">
                <source id="video_source" src="{{ $link }}" type="application/x-mpegURL">
                Tu navegador no soporta la etiqueta de video.
            </video>
        </div>

        <div class="video-details-container">
            <div class="video-title">
                <h5>{{ $stream->titulo }}</h5>
            </div>
            <div class="uploader-info">
                <div class="uploader-photo">
                    @if ($stream->canal->user && $stream->canal->user->foto)
                        <img src="{{ asset($stream->canal->user->foto) }}"
                            alt="Foto de perfil de {{ $stream->canal->user->name }}" class="rounded-circle">
                    @else
                        <img src="{{ asset('img/default-user.png') }}" alt="Foto de perfil por defecto"
                            class="rounded-circle">
                    @endif
                </div>
                <div class="uploader-name">
                    <p class="m-0"><strong>Subido por:</strong> {{ $stream->canal->user->name }}
                        ({{ $stream->canal->user->email }})</p>
                    <div class="live-status">
                        @if ($stream->activo)
                            <i class="fas fa-circle text-success"></i> Live
                        @else
                            <i class="fas fa-circle text-danger"></i> Offline
                        @endif
                    </div>
                </div>
            </div>
            <div class="video-info">
                <p class="m-0"><strong>Canal:</strong> <a class="custom-link"
                        href="{{ route('canal.detalle', ['id' => $stream->canal->id]) }}">
                        {{ $stream->canal->nombre }} <i class="fas fa-link"></i>
                    </a></p>
                <p class="m-0"><strong>Fecha de subida:</strong>
                    {{ \Carbon\Carbon::parse($stream->created_at)->format('d/m/Y H:i') }}</p>
                <p class="m-0">Stream-key</p>
                <div class="input-group my-2">
                    <input type="password" id="streamKey" class="search-bar stream-key-input"
                        value="{{ $stream->canal->stream_key }}" readonly>
                    <button class="btn btn-info btn-stream-key" type="button" onclick="toggleStreamKeyVisibility()">
                        <i id="toggleIcon" class="fas fa-eye"></i>
                    </button>
                </div>
                <p class="text-justify m-2">{!! nl2br(e($stream->descripcion)) !!}</p>
            </div>
            <div class="video-actions text-center   my-4">
                <a href="{{ route('stream.editar.formulario', ['id' => $stream->id]) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
                @if ($stream->activo)
                    <button class="btn btn-danger" disabled>
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </button>
                @else
                    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                        <i class="fas fa-trash-alt"></i> Eliminar
                    </a>
                @endif
            </div>

            @include('modals.delete-stream-modal', ['stream' => $stream])
        </div>
        @if (session('success'))
            <div class="alert alert-success text-center mx-auto" style="max-width: 500px; margin-top: 0 !important;">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var video = document.getElementById('video');
            var videoSource = document.getElementById('video_source');
            var url_hls = videoSource.src;

            if (Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource(url_hls);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    video.play();
                });
            } else {
                video.load();
                video.play();
            }
        });

        function toggleStreamKeyVisibility() {
            const streamKeyInput = document.getElementById('streamKey');
            const toggleIcon = document.getElementById('toggleIcon');
            if (streamKeyInput.type === 'password') {
                streamKeyInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                streamKeyInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
