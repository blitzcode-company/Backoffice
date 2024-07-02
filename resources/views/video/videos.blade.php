@extends('layouts.app')

@section('content')
    <div class="search-container">
        <form action="{{ route('videos-nombre') }}" method="POST">
            @csrf
            <input type="search" name="nombre" placeholder="Buscar video por nombre" class="search-bar" required>
            <button type="submit" class="btn-info"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="text-center my-4">
        <a href="{{ route('videos.subir') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Video
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="max-width: 500px; margin-top: 0 !important;">
            {{ session('success') }}
        </div>
    @endif

    <div class="video-list-container">
        @if ($videos->isEmpty())
            <p>No hay videos disponibles.</p>
        @else
            @foreach ($videos as $video)
                <div class="card mb-3 video-card">
                    <div class="video-thumbnail">
                        @if ($video->miniatura)
                            <img src="{{ $video->miniatura }}" alt="Miniatura del video">
                        @else
                            <img src="{{ asset('img/video-default.png') }}" alt="Miniatura por defecto">
                        @endif
                    </div>
                    <div class="video-info">
                        <h2 class="video-title">{{ $video->titulo }}</h2>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('video', ['id' => $video->id]) }}" class="btn btn-info">
                            <i class="fas fa-info-circle"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
