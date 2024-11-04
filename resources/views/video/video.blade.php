@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Información del Video</span>
    </div>
    <div class="video-page-container">

        <div class="video-player-container">
            <video controls autoplay class="video-player">
                <source src="{{ $video->link }}" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
        </div>

        <div class="video-details-container">
            <div class="video-title">
                <h5>{{ $video->titulo }}</h5>
            </div>
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
                <p class="m-0"><strong>Duración:</strong>
                    {{ floor($video->duracion / 60) }}:{{ str_pad($video->duracion % 60, 2, '0', STR_PAD_LEFT) }}
                </p>
                <p class="m-0"><strong>Visitas :</strong> {{ $video->visitas_count }}</p>

                <div class="video-tags">
                    @if ($video->etiquetas->isEmpty())
                        <p class="m-0"><strong><i class="fas fa-tags"></i>Etiquetas:</strong> No tiene etiquetas.</p>
                    @else
                        <p class="m-0"><strong><i class="fas fa-tags"></i>Etiquetas:</strong>
                            @foreach ($video->etiquetas as $etiqueta)
                                {{ $etiqueta->nombre }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </p>
                    @endif
                </div>
                <p class="text-justify m-2">{!! nl2br(e($video->descripcion)) !!}</p>

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

            <div class="video-actions text-center my-4">
                <a href="{{ route('comentarios.listado', ['id' => $video->id]) }}" class="btn btn-primary">
                    <i class="fas fa-comments"></i> Comentarios
                </a>
                <a href="{{ route('video.editar.formulario', ['id' => $video->id]) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
                <button class="btn {{ $video->bloqueado ? 'btn-secondary' : 'btn-warning' }}" data-bs-toggle="modal"
                    data-bs-target="#confirmBlockModalVideo{{ $video->id }}" data-id="{{ $video->id }}">
                    <i class="fas fa-ban"></i> {{ $video->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                </button>
            </div>
            @include('modals.delete-video-modal', ['video' => $video])
            @include('modals.blockModalVideo', ['video' => $video])
        </div>
        @if (session('success'))
        <div class="alert alert-success text-center mx-auto" style="max-width: 500px; margin-top: 0 !important;">
            {{ session('success') }}
        </div>
    @endif
    </div>
@endsection
