@extends('layouts.app')

@section('content')
    <div class="titulo">Videos de la Etiqueta: {{ $etiqueta->nombre }}</div>



    @if (session('success'))
        <div class="alert alert-success text-center mx-auto" style="max-width: 500px; margin-top: 0 !important;">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-center">
        {{ $videos->links('vendor.pagination.pagination') }}
    </div>

    <div class="video-list-container">
        <div class="video-list">
            @if ($videos->isEmpty())
                <p>No hay videos disponibles.</p>
            @else
                @foreach ($videos as $video)
                    <div class="card video-card">
                        <div class="video-thumbnail">
                            <a href="{{ route('video.detalle', ['id' => $video->id]) }}">
                                @if ($video->miniatura)
                                    <img src="{{ $video->miniatura }}" alt="Miniatura del video">
                                @else
                                    <img src="{{ asset('img/video-default.png') }}" alt="Miniatura por defecto">
                                @endif
                            </a>
                        </div>
                        <div class="video-info">
                            <h2 class="video-title">{{ $video->titulo }}</h2>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="d-flex justify-content-center">
        {{ $videos->links('vendor.pagination.pagination') }}
    </div>
@endsection
