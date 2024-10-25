@extends('layouts.app')

@section('content')
    <div class="titulo">Videos por Etiquetas</div>
    <div class="d-flex justify-content-center">
        {{ $etiquetas->links('vendor.pagination.pagination') }}
    </div>
    <div class="etiquetas-container">
        <div class="etiquetas-list-content">
            @foreach ($etiquetas as $etiqueta)
                <div class="etiqueta-card">
                    <div class="etiqueta-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div class="etiqueta-content">
                        <h3>{{ $etiqueta->nombre }}</h3>
                        <p>Cantidad de videos: <strong>{{ $etiqueta->videos_count }}</strong></p>
                        <a href="{{ route('video.etiqueta', $etiqueta->id) }}" class="ver-videos-btn">
                            <i class="fas fa-play-circle"></i> Ver Videos
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="d-flex justify-content-center">
        {{ $etiquetas->links('vendor.pagination.pagination') }}
    </div>
@endsection
