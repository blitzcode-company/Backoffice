@extends('layouts.app')

@section('content')
    <div class="comments-page-container">

        <div class="navigation-buttons mb-4">
            <button onclick="window.history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Anterior
            </button>
        </div>

        <h4 class="mt-4 text-center">Responder al comentario #{{ $comentario->id }}</h4>
        <br>

        <form action="{{ route('comentarios.responder') }}" method="POST" class="">
            @csrf
            <input type="hidden" name="video_id" value="{{ $video->id }}">
            <div class="form-group">
                <label for="usuario_id">ID del Usuario:</label>
                <input type="number" class="form-control no-arrows" id="usuario_id" name="usuario_id" required
                    min="1">
            </div>
            <div class="form-group">
                <label for="mensaje">Comentario:</label>
                <textarea class="form-control" id="mensaje" name="mensaje" rows="3" required></textarea>
            </div>
            <input type="hidden" id="respuesta_id" name="respuesta_id" value="{{ $comentario->id }}">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-save"></i> Guardar Comentario
            </button>
        </form>

        @if (!$comentario)
            <div class="alert alert-danger">
                <p>El comentario que estás tratando de ver no existe o ha sido eliminado.</p>
            </div>
        @else
            @if ($errors->any())
                <div class="alert alert-danger">
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
            <br> <br>
            <div class="list-group">
                <div class="list-group-item text-left">
                    <div class="comment-header">
                        <p><strong>ID:</strong> #{{ $comentario->id }}</p>
                        <p><strong>Publicado por:</strong> {{ $comentario->user->name }}#{{ $comentario->user->id }}</p>
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
                        @if ($comentario->trashed())
                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                data-target="#restoreCommentModal{{ $comentario->id }}" data-id="{{ $comentario->id }}">
                                <i class="fas fa-undo"></i> Restaurar
                            </button>
                        @else
                            <button class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#confirmDeleteModal{{ $comentario->id }}" data-id="{{ $comentario->id }}">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        @endif
                        <a href="" class="btn custom-edit-btn btn-sm" data-toggle="modal" data-target="#editCommentModal">
                            <i class="fas fa-edit"></i> Editar
                        </a>

                        <button class="btn {{ $comentario->bloqueado ? 'btn-secondary' : 'btn-warning' }} btn-sm"
                            data-toggle="modal" data-target="#confirmBlockModal{{ $comentario->id }}"
                            data-id="{{ $comentario->id }}">
                            <i class="fas fa-ban"></i> {{ $comentario->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                        </button>
                    </div>
                    @include('modals.delete-comment', ['comentario' => $comentario])
                    @include('modals.restore-comment', ['comentario' => $comentario])
                    @include('modals.block-comment', ['comentario' => $comentario])
                    <br>
                    @if ($comentario->respuestas->isNotEmpty())
                        <ul class="list-group">
                            @foreach ($comentario->respuestas as $respuesta)
                                <li class="list-group-item ml-4 text-left respuesta-box">
                                    <div class="comment-header">
                                        <p><strong>ID:</strong> #{{ $respuesta->id }}</p>
                                        <p><strong>Respuesta de:</strong>
                                            {{ $respuesta->user->name }}#{{ $respuesta->user->id }}</p>
                                        <p><strong>Fecha:</strong> {{ $respuesta->created_at->format('d/m/Y H:i') }}</p>
                                        @if ($respuesta->deleted_at)
                                            <p><strong>Fecha de Eliminación:</strong>
                                                {{ $respuesta->deleted_at->format('d/m/Y H:i') }}</p>
                                        @endif
                                    </div>
                                    <div class="comment-body comment-box">
                                        <p @if ($respuesta->trashed()) style="text-decoration: line-through;" @endif>
                                            {{ $respuesta->mensaje }}
                                        </p>
                                    </div>
                                    <div class="comment-actions">
                                        <a href="{{ route('comentarios.ver', ['comentario_id' => $respuesta->id]) }}"
                                            class="btn btn-primary comment btn-sm">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                        @if ($respuesta->trashed())
                                            <button class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#restoreResponseModal{{ $respuesta->id }}"
                                                data-id="{{ $respuesta->id }}">
                                                <i class="fas fa-undo"></i> Restaurar
                                            </button>
                                        @else
                                            <button class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#confirmDeleteModalRespuesta{{ $respuesta->id }}"
                                                data-id="{{ $respuesta->id }}">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        @endif
                                        <button
                                            class="btn {{ $respuesta->bloqueado ? 'btn-secondary' : 'btn-warning' }} btn-sm"
                                            data-toggle="modal"
                                            data-target="#confirmBlockModalRespuesta{{ $respuesta->id }}"
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
                    @else
                        <p>No hay respuestas aún.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
    @include('modals.edit_comment')
@endsection
