@extends('layouts.app')

@section('content')
<div class="titulo">Comentarios del Video</div>
    <div class="comments-page-container">
        <div class="navigation-buttons mb-4">
            <a href="{{ route('video.detalle', ['id' => $video->id]) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Ir a detalles
            </a>
        </div>
        <div class="video-player-container">
            <video controls autoplay class="video-player">
                <source src="{{ $video->link }}" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
        </div>

        <header class="comments-header">
            <h1 class="text-center">Comentarios del Video: <strong>{{ $video->titulo }}</strong>(#{{ $video->id }})</h1>
        </header>

        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createCommentModal">
                <i class="fas fa-plus"></i> Nuevo Comentario
            </a>
        </div>

        <div class="comments-list mt-4">
            @if ($comentarios->isEmpty())
                <p>No hay comentarios para este video.</p>
            @else
                <ul class="list-group">
                    @foreach ($comentarios as $comentario)
                        <li class="list-group-item">
                            <div class="comment-header">
                                <p><strong>ID: </strong>#{{ $comentario->id }}</p>
                                <p><strong>Publicado por:</strong> {{ $comentario->user->name }}
                                    (#{{ $comentario->user->id }})
                                </p>
                                <p><strong>Fecha:</strong> {{ $comentario->created_at->format('d/m/Y H:i') }}</p>
                                @if ($comentario->deleted_at)
                                    <p><strong>Fecha de Eliminación:</strong>
                                        {{ $comentario->deleted_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                            <div class="comment-body comment-box">
                                <p @if ($comentario->trashed()) style="text-decoration: line-through;" @endif>
                                    {{ $comentario->mensaje }}
                                </p>
                            </div>

                            <div class="comment-actions">
                                <a href="{{ route('comentarios.ver', ['comentario_id' => $comentario->id]) }}"
                                    class="btn btn-primary comment btn-sm">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                @if ($comentario->trashed())
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#restoreCommentModal{{ $comentario->id }}"
                                        data-id="{{ $comentario->id }}">
                                        <i class="fas fa-undo"></i> Restaurar
                                    </button>
                                @else
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal{{ $comentario->id }}"
                                        data-id="{{ $comentario->id }}">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                @endif

                                <button class="btn {{ $comentario->bloqueado ? 'btn-secondary' : 'btn-warning' }} btn-sm"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmBlockModal{{ $comentario->id }}"
                                    data-id="{{ $comentario->id }}">
                                    <i class="fas fa-ban"></i> {{ $comentario->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                                </button>
                                
                            </div>

                            @if ($comentario->respuestas->isNotEmpty())
                                <ul class="list-group mt-3">
                                    @foreach ($comentario->respuestas as $respuesta)
                                        <li class="list-group-item ml-4 respuesta-box">
                                            <div class="comment-header">
                                                <p><strong>ID: </strong>#{{ $respuesta->id }}</p>
                                                <p><strong>Respuesta de:</strong> {{ $respuesta->user->name }}
                                                    (#{{ $respuesta->user->id }})
                                                </p>
                                                <p><strong>Fecha:</strong>
                                                    {{ $respuesta->created_at->format('d/m/Y H:i') }}</p>
                                                @if ($respuesta->deleted_at)
                                                    <p><strong>Fecha de Eliminación:</strong>
                                                        {{ $respuesta->deleted_at->format('d/m/Y H:i') }}</p>
                                                @endif
                                            </div>
                                            <div class="comment-body comment-box">
                                                <p
                                                    @if ($respuesta->trashed()) style="text-decoration: line-through;" @endif>
                                                    {{ $respuesta->mensaje }}
                                                </p>
                                            </div>

                                            <div class="comment-actions">
                                                <a href="{{ route('comentarios.ver', ['comentario_id' => $respuesta->id]) }}"
                                                    class="btn btn-primary comment btn-sm">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                @if ($respuesta->trashed())
                                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#restoreResponseModal{{ $respuesta->id }}"
                                                        data-id="{{ $respuesta->id }}">
                                                        <i class="fas fa-undo"></i> Restaurar
                                                    </button>
                                                @else
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteModalRespuesta{{ $respuesta->id }}"
                                                        data-id="{{ $respuesta->id }}">
                                                        <i class="fas fa-trash"></i> Eliminar
                                                    </button>
                                                @endif
                                                <button
                                                    class="btn {{ $respuesta->bloqueado ? 'btn-secondary' : 'btn-warning' }} btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmBlockModalRespuesta{{ $respuesta->id }}"
                                                    data-id="{{ $respuesta->id }}">
                                                    <i class="fas fa-ban"></i>
                                                    {{ $respuesta->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                                                </button>
                                            </div>
                                        </li>
                                        @include('modals.delete-response', ['comentario' => $comentario])
                                        @include('modals.restore-response', ['comentario' => $comentario])
                                        @include('modals.block-response', ['comentario' => $comentario])
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                        @include('modals.delete-comment', ['comentario' => $comentario])
                        @include('modals.restore-comment', ['comentario' => $comentario])
                        @include('modals.block-comment', ['comentario' => $comentario])
                    @endforeach
                </ul>
            @endif
        </div>
        @include('modals.create-comment', ['video' => $video])
    </div>
@endsection
