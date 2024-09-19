@extends('layouts.app')

@section('content')
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
                    <p><strong>Subido por:</strong> {{ $video->canal->user->name }} ({{ $video->canal->user->email }})</p>
                </div>
            </div>
            <div class="video-info">
                <p> {{ $video->descripcion }}</p>
                <p><strong>Canal:</strong> {{ $video->canal->nombre }}</p>
                <p><strong>Visitas:</strong> {{ $video->visitas_count }}</p>
                <div class="video-thumbnail-small mt-4">
                    <h3>Miniatura</h3>
                    @if ($video->miniatura)
                        <img src="{{ $video->miniatura }}" alt="Miniatura de {{ $video->titulo }}" class="img-fluid">
                    @else
                        <img src="{{ asset('img/video-default.png') }}" alt="Miniatura por defecto" class="img-fluid">
                    @endif
                </div>
                <div class="video-tags">
                    @if ($video->etiquetas->isEmpty())
                        <p><strong>Etiquetas:</strong> No tiene etiquetas.</p>
                    @else
                        <p><strong>Etiquetas:</strong>
                            @foreach ($video->etiquetas as $etiqueta)
                                {{ $etiqueta->nombre }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </p>
                    @endif
                </div>

                <div class="video-ratings">
                    <h3>Puntuaciones</h3>
                    <ul>
                        <li><img src="{{ asset('img/emojis/5.png') }}" alt="5" class="img-fluid emoji">
                            {{ $video->puntuacion_5 }}</li>
                        <li><img src="{{ asset('img/emojis/4.png') }}" alt="4" class="img-fluid emoji">
                            {{ $video->puntuacion_4 }}</li>
                        <li><img src="{{ asset('img/emojis/3.png') }}" alt="3" class="img-fluid emoji">
                            {{ $video->puntuacion_3 }}</li>
                        <li><img src="{{ asset('img/emojis/2.png') }}" alt="2" class="img-fluid emoji">
                            {{ $video->puntuacion_2 }}</li>
                        <li><img src="{{ asset('img/emojis/1.png') }}" alt="1" class="img-fluid emoji">
                            {{ $video->puntuacion_1 }}</li>
                    </ul>
                </div>
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
            <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                <i class="fas fa-trash-alt"></i> Eliminar
            </a>
        </div>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres eliminar este video?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('video.eliminar', ['id' => $video->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
