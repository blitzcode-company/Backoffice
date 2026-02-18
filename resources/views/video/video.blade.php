@extends('layouts.app')

@section('content')
    <div class="admin-video-container">
        {{-- Header --}}
        <div class="page-header">
            <div class="d-flex align-items-center gap-3">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2>Gestión de Video</h2>
                    <span class="text-muted small">ID: {{ $video->id }}</span>
                </div>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if ($video->bloqueado)
                    <span class="badge bg-danger">Bloqueado</span>
                @else
                    <span class="badge bg-success">Activo</span>
                @endif
                
                @if ($video->acceso == 'publico')
                    <span class="badge bg-primary"><i class="fas fa-globe-americas me-1"></i> Público</span>
                @else
                    <span class="badge bg-secondary"><i class="fas fa-lock me-1"></i> Privado</span>
                @endif
            </div>
        </div>

        {{-- Alertas --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Columna Principal: Video y Datos --}}
            <div class="col-lg-8">
                <div class="content-section p-0 overflow-hidden" style="background: #000;">
                    <div class="ratio ratio-16x9">
                        <video controls autoplay class="w-100 h-100">
                            <source src="{{ $video->link }}" type="video/mp4">
                            Tu navegador no soporta la etiqueta de video.
                        </video>
                    </div>
                </div>

                <div class="content-section">
                    <h3 class="mb-3">{{ $video->titulo }}</h3>
                    <div class="d-flex gap-4 text-muted mb-4 small">
                        <span><i class="fas fa-eye me-1"></i> {{ number_format($video->visitas_count) }} visitas</span>
                        <span><i class="fas fa-clock me-1"></i> {{ floor($video->duracion / 60) }}:{{ str_pad($video->duracion % 60, 2, '0', STR_PAD_LEFT) }} min</span>
                        <span><i class="fas fa-calendar me-1"></i> {{ $video->created_at->format('d M, Y') }}</span>
                    </div>
                    
                    <h5 class="text-muted mb-2" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Descripción</h5>
                    <p class="mb-4" style="white-space: pre-line;">{{ $video->descripcion }}</p>

                    <h5 class="text-muted mb-2" style="font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Etiquetas</h5>
                    <div>
                        @forelse ($video->etiquetas as $etiqueta)
                            <span class="tag-badge">{{ $etiqueta->nombre }}</span>
                        @empty
                            <span class="text-muted fst-italic">Sin etiquetas.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Columna Lateral: Acciones y Autor --}}
            <div class="col-lg-4">
                <div class="content-section">
                    <div class="d-grid gap-2">
                        <a href="{{ route('video.editar.formulario', ['id' => $video->id]) }}" class="btn btn-primary fw-bold">
                            <i class="fas fa-pen me-2"></i> Editar Video
                        </a>
                        <a href="{{ route('comentarios.listado', ['id' => $video->id]) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-comments me-2"></i> Comentarios
                        </a>
                        <button class="btn {{ $video->bloqueado ? 'btn-success' : 'btn-warning' }}" data-bs-toggle="modal" data-bs-target="#confirmBlockModalVideo{{ $video->id }}">
                            <i class="fas {{ $video->bloqueado ? 'fa-unlock' : 'fa-ban' }} me-2"></i> {{ $video->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                        </button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                            <i class="fas fa-trash me-2"></i> Eliminar
                        </button>
                    </div>
                </div>

                <div class="content-section">
                    <h5 class="mb-3" style="font-size: 1rem;">Creador</h5>
                    <div class="d-flex align-items-center">
                        <img src="{{ $video->canal->user->foto ? asset($video->canal->user->foto) : asset('img/default-user.png') }}" 
                             class="rounded-circle me-3" width="50" height="50" style="object-fit: cover;">
                        <div>
                            <div class="fw-bold">{{ $video->canal->user->name }}</div>
                            <div class="text-muted small">{{ $video->canal->user->email }}</div>
                            <a href="{{ route('canal.detalle', ['id' => $video->canal->id]) }}" class="small text-decoration-none">Ver Canal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('modals.delete-video-modal', ['video' => $video])
        @include('modals.blockModalVideo', ['video' => $video])
    </div>
@endsection
