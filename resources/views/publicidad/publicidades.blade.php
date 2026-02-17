@extends('layouts.app')

@section('content')
    <div class="titulo">Lista de Publicidades</div>
    <div class="publicidad-container">
        <div class="row align-items-center mb-4 g-3">
            <div class="col-md-3 d-none d-md-block"></div>
            
            <div class="col-12 col-md-6 d-flex justify-content-center">
                <form action="{{ route('publicidad.listar') }}" method="GET" class="w-100" style="max-width: 600px;">
                    <div class="input-group shadow-sm">
                        <input type="search" name="nombre" placeholder="Buscar por empresa..." class="form-control search-bar border-end-0"
                            value="{{ request('nombre') }}" aria-label="Buscar publicidad">
                        <button type="submit" class="btn btn-info border-start-0" style="max-width: 60px;">
                            <i class="fas fa-search text-white"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-12 col-md-3 d-flex justify-content-center">
                <a href="{{ route('publicidad.crear.formulario') }}" class="btn btn-primary shadow-sm d-flex align-items-center gap-2" style="width: auto; padding: 10px 20px;">
                    <i class="fas fa-ad"></i> <span>Nueva Publicidad</span>
                </a>
            </div>
        </div>

        @if (session('mensaje'))
            <div class="alert alert-success text-center mx-auto mb-4" style="max-width: 800px;">
                {{ session('mensaje') }}
            </div>
        @endif

        <div class="d-flex justify-content-center">
            {{ $publicidades->links('vendor.pagination.pagination') }}
        </div>
        <div class="publicidad-lista">
            @forelse($publicidades as $publicidad)
                <div class="publicidad-item">
                    <div class="publicidad-datos">
                        <h2 class="publicidad-empresa">{{ $publicidad->empresa }}</h2>
                        <p class="publicidad-prioridad">
                            Prioridad:
                            @if ($publicidad->prioridad == 1)
                                Alta
                            @elseif ($publicidad->prioridad == 2)
                                Media
                            @elseif ($publicidad->prioridad == 3)
                                Baja
                            @else
                                No definida
                            @endif
                        </p>
                    </div>
                    <div class="video-datos">
                        <h3 class="video-titulo">Video Asociado:</h3>
                        @foreach ($publicidad->video as $video)
                            <p>ID: {{ $video->id }} | Título: {{ $video->titulo }}</p>
                        @endforeach
                        <div class="d-flex align-items-center">
                            <a href="{{ route('video.detalle', ['id' => $video->id]) }}" class="button-info" title="Ver Video">
                                <i class="fas fa-info-circle"></i>
                            </a>
                            <a href="{{ route('publicidad.editar.formulario', ['id' => $publicidad->id]) }}" class="button-info ms-2" title="Editar Publicidad">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="button-info button-delete ms-2" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-{{ $publicidad->id }}" title="Eliminar Publicidad">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @include('modals.delete-publicidad', ['publicidad' => $publicidad])
            @empty
                <p>No hay publicidades disponibles.</p>
            @endforelse
        </div>
    </div>
@endsection
