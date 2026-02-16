@extends('layouts.app')

@section('content')
    <div class="titulo">Streams de Blitzvideo</div>
    <div class="search-container">
        <form action="{{ route('stream.nombre') }}" method="POST">
            @csrf
            <input type="search" name="nombre" placeholder="Buscar stream por nombre" class="search-bar" required>
            <button type="submit" class="btn-info"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="text-center my-4">
        <a href="{{ route('stream.crear.formulario') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Stream
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success text-center mx-auto" style="max-width: 500px; margin-top: 0 !important;">
            {{ session('success') }}
        </div>
    @endif


    <div class="d-flex justify-content-center">
        {{ $streams->links('vendor.pagination.pagination') }}
    </div>

    <div class="video-list-container">
        <div class="video-list">
            @if ($streams->isEmpty())
                <p>No hay streams disponibles.</p>
            @else
                @foreach ($streams as $stream)
                    <div class="card video-card">
                        <div class="video-thumbnail position-relative">
                            <a href="{{ route('stream.detalle', ['id' => $stream->id]) }}">
                                @if ($stream->miniatura)
                                    <img src="{{ $stream->miniatura }}" alt="Miniatura del stream">
                                @else
                                    <img src="{{ asset('img/video-default.png') }}" alt="Miniatura por defecto">
                                @endif
                            </a>
                            <div class="video-duration">
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
                            <h2 class="video-title">{{ $stream->titulo }}</h2>
                            <p class="m-0 text-muted small">{{ $stream->canal->user->name ?? 'Usuario Desconocido' }}</p>
                            <p class="m-0 text-muted small">Key: {{ $stream->canal->stream_key ?? 'N/A' }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $streams->links('vendor.pagination.pagination') }}
    </div>

@endsection
