@extends('layouts.app')

@section('content')
    <div class="admin-video-container mb-0 pb-0" style="border-bottom: none;">
        <div class="page-header mb-0 border-0">
            <div class="d-flex align-items-center gap-3">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2>Comentarios del Video</h2>
                    <span class="text-muted small">{{ $video->titulo }} (#{{ $video->id }})</span>
                </div>
            </div>
        </div>
    </div>

    <div class="comments-container">
        <div class="video-preview-section">
            <div class="ratio ratio-16x9">
                <video controls class="w-100 h-100">
                <source src="{{ $video->link }}" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Discusión ({{ $comentarios->count() }})</h4>
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCommentModal">
                <i class="fas fa-plus me-2"></i> Nuevo Comentario
            </a>
        </div>

        <div class="comments-list">
            @if ($comentarios->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-comments fa-3x mb-3"></i>
                    <p>No hay comentarios para este video aún.</p>
                </div>
            @else
                    @foreach ($comentarios as $comentario)
                    <div class="comment-thread">
                        {{-- Comentario Principal --}}
                        <div class="comment-card">
                            <div class="comment-header">
                                <img src="{{ $comentario->user->foto ? asset($comentario->user->foto) : asset('img/default-user.png') }}" 
                                     alt="User" class="avatar-img">
                                
                                <div class="user-details">
                                    <h5 class="user-name">{{ $comentario->user->name }}</h5>
                                    <div class="comment-meta">
                                        <span>{{ $comentario->created_at->format('d M Y, H:i') }}</span>
                                        <span>•</span>
                                        <span>ID: #{{ $comentario->id }}</span>
                                        @if ($comentario->deleted_at)
                                            <span class="badge bg-danger ms-2">Eliminado</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="comment-content">
                                <p class="mb-0" @if ($comentario->trashed()) style="text-decoration: line-through; opacity: 0.6;" @endif>
                                    {{ $comentario->mensaje }}
                                </p>
                            </div>

                            <div class="comment-footer">
                                <a href="{{ route('comentarios.ver', ['comentario_id' => $comentario->id]) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-reply me-1"></i> Ver Hilo
                                </a>
                                
                                @if ($comentario->trashed())
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#restoreCommentModal{{ $comentario->id }}"
                                        data-id="{{ $comentario->id }}">
                                        <i class="fas fa-undo me-1"></i> Restaurar
                                    </button>
                                @else
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal{{ $comentario->id }}"
                                        data-id="{{ $comentario->id }}">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                @endif

                                <button class="btn {{ $comentario->bloqueado ? 'btn-outline-secondary' : 'btn-outline-warning' }} btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#confirmBlockModal{{ $comentario->id }}"
                                    data-id="{{ $comentario->id }}">
                                    <i class="fas fa-ban me-1"></i> {{ $comentario->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                                </button>
                            </div>
                        </div>

                        {{-- Respuestas --}}
                        @if ($comentario->respuestas->isNotEmpty())
                            <div class="replies-wrapper">
                                    @foreach ($comentario->respuestas as $respuesta)
                                    <div class="comment-card">
                                        <div class="comment-header">
                                            <img src="{{ $respuesta->user->foto ? asset($respuesta->user->foto) : asset('img/default-user.png') }}" 
                                                 alt="User" class="avatar-img">
                                            
                                            <div class="user-details">
                                                <h6 class="user-name">{{ $respuesta->user->name }}</h6>
                                                <div class="comment-meta">
                                                    <span>{{ $respuesta->created_at->format('d M, H:i') }}</span>
                                                    @if ($respuesta->deleted_at)
                                                        <span class="badge bg-danger ms-2">Eliminado</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="comment-content">
                                            <p class="mb-0" @if ($respuesta->trashed()) style="text-decoration: line-through; opacity: 0.6;" @endif>
                                                    {{ $respuesta->mensaje }}
                                                </p>
                                            </div>

                                        <div class="comment-footer">
                                                <a href="{{ route('comentarios.ver', ['comentario_id' => $respuesta->id]) }}"
                                               class="btn btn-outline-primary btn-sm" title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                                </a>
                                                @if ($respuesta->trashed())
                                                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#restoreResponseModal{{ $respuesta->id }}"
                                                    data-id="{{ $respuesta->id }}" title="Restaurar">
                                                    <i class="fas fa-undo"></i>
                                                    </button>
                                                @else
                                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteModalRespuesta{{ $respuesta->id }}"
                                                    data-id="{{ $respuesta->id }}" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                                <button
                                                class="btn {{ $respuesta->bloqueado ? 'btn-outline-secondary' : 'btn-outline-warning' }} btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmBlockModalRespuesta{{ $respuesta->id }}"
                                                data-id="{{ $respuesta->id }}" title="Bloquear/Desbloquear">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </div>
                                    </div>
                                        @include('modals.delete-response', ['comentario' => $comentario])
                                        @include('modals.restore-response', ['comentario' => $comentario])
                                        @include('modals.block-response', ['comentario' => $comentario])
                                     
                                    @endforeach
                            </div>
                            @endif
                    </div>
                        @include('modals.delete-comment', ['comentario' => $comentario])
                        @include('modals.restore-comment', ['comentario' => $comentario])
                        @include('modals.block-comment', ['comentario' => $comentario])
                    @endforeach
            @endif
        </div>
        @include('modals.create-comment', ['video' => $video])
    </div>
@endsection
