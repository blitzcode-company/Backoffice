@extends('layouts.app')

@section('content')
    <div class="titulo">Información del Video</div>
    <div class="video-page-container">
        <div class="navigation-buttons mb-4">
            <a href="{{ route('video.listar') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Ir a videos
            </a>
        </div>
        <header class="video-title">
            <h1>{{ $video->titulo }}</h1>
        </header>
        <div class="video-player-container">
            <video controls autoplay class="video-player">
                <source src="{{ $video->link }}" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
        </div>

        <div class="video-details-container">
            <div class="uploader-info">
                <div class="uploader-photo">
                    @if ($video->canal->user && $video->canal->user->foto)
                        <img src="{{ asset($video->canal->user->foto) }}"
                            alt="Foto de perfil de {{ $video->canal->user->name }}" class="rounded-circle">
                    @else
                        <img src="{{ asset('img/default-user.png') }}" alt="Foto de perfil por defecto"
                            class="rounded-circle">
                    @endif
                </div>
                <div class="uploader-name">
                    <p class="m-0"><strong>Subido por:</strong> {{ $video->canal->user->name }}
                        ({{ $video->canal->user->email }}) </p>
                </div>
            </div>
            <div class="video-info">
                <p class="m-0"><strong>Canal:</strong> <a class="custom-link"
                        href="{{ route('canal.detalle', ['id' => $video->canal->id]) }}">
                        {{ $video->canal->nombre }} <i class="fas fa-link"></i>
                    </a></p>
                <p class="m-0"><strong>Visitas :</strong> {{ $video->visitas_count }}</p>
                <div class="video-thumbnail-small mt-4">
                    <h4 class="m-0">Miniatura</h4>
                    @if ($video->miniatura)
                        <img src="{{ $video->miniatura }}" alt="Miniatura de {{ $video->titulo }}" class="img-fluid m-0">
                    @else
                        <img src="{{ asset('img/video-default.png') }}" alt="Miniatura por defecto" class="img-fluid m-0">
                    @endif
                </div>
                <div class="video-tags">
                    @if ($video->etiquetas->isEmpty())
                        <p><strong><i class="fas fa-tags"></i>Etiquetas:</strong> No tiene etiquetas.</p>
                    @else
                        <p><strong><i class="fas fa-tags"></i>Etiquetas:</strong>
                            @foreach ($video->etiquetas as $etiqueta)
                                {{ $etiqueta->nombre }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </p>
                    @endif
                </div>
                <p class="text-justify">{!! nl2br(e($video->descripcion)) !!}</p>

                <div class="video-ratings">
                    <h4>Puntuaciones</h4>
                    <ul>
                        <li class="rating-item"><img src="{{ asset('img/emojis/5.png') }}" alt="5"
                                class="img-fluid emoji">
                            {{ $video->puntuacion_5 }}</li>
                        <li class="rating-item"><img src="{{ asset('img/emojis/4.png') }}" alt="4"
                                class="img-fluid emoji">
                            {{ $video->puntuacion_4 }}</li>
                        <li class="rating-item"><img src="{{ asset('img/emojis/3.png') }}" alt="3"
                                class="img-fluid emoji">
                            {{ $video->puntuacion_3 }}</li>
                        <li class="rating-item"><img src="{{ asset('img/emojis/2.png') }}" alt="2"
                                class="img-fluid emoji">
                            {{ $video->puntuacion_2 }}</li>
                        <li class="rating-item"><img src="{{ asset('img/emojis/1.png') }}" alt="1"
                                class="img-fluid emoji">
                            {{ $video->puntuacion_1 }}</li>
                    </ul>
                    <p><strong>Promedio de Puntuaciones:</strong> {{ $video->promedio_puntuaciones }}</p>
                </div>
            </div>

            <div class="video-actions text-center">
                <a href="{{ route('comentarios.listado', ['id' => $video->id]) }}" class="btn btn-primary">
                    <i class="fas fa-comments"></i> Comentarios
                </a>
                <a href="{{ route('video.editar.formulario', ['id' => $video->id]) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
            </div>
            @include('modals.delete-video-modal', ['video' => $video])
        @endsection
