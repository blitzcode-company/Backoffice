@extends('layouts.app')

@section('content')
    <div class="titulo">Lista de Publicidades</div>
    <div class="publicidad-container">
        @if (session('mensaje'))
            <div class="alerta-exito">
                {{ session('mensaje') }}
            </div>
        @endif
        <div class="search-container">
            <form action="{{ route('publicidad.listar') }}" method="GET">
                <input type="search" name="nombre" placeholder="Buscar por empresa" class="search-bar"
                    value="{{ request('nombre') }}">
                <button type="submit" class="btn-info"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="create-user-button-container text-center my-4">
            <a href="{{ route('publicidad.crear.formulario') }}" class="btn btn-primary">
                <i class="fas fa-ad"></i> Nueva Publicidad
            </a>
        </div>

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
                            <a href="{{ route('publicidad.editar.formulario', ['id' => $publicidad->id]) }}" class="button-info ml-2" title="Editar Publicidad">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                        
                    </div>
                </div>
            @empty
                <p>No hay publicidades disponibles.</p>
            @endforelse
        </div>
    </div>
@endsection
