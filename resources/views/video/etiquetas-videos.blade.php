@extends('layouts.app')

@section('content')
    <div class="etiquetas-container">
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
@endsection
