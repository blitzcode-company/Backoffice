@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Videos de "{{ $videos[0]->canal->nombre }}"</span>
    </div>

    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-3 d-none d-md-block"></div>
        
        <div class="col-12 col-md-6 d-flex justify-content-center">
            <form action="{{ route('video.canal', ['id' => $canalId ?? '']) }}" method="GET" class="w-100" style="max-width: 600px;">
                <div class="input-group shadow-sm">
                    <input type="search" name="titulo" placeholder="Buscar video por título..." class="form-control search-bar border-end-0"
                        required aria-label="Buscar video">
                    <button type="submit" class="btn btn-info border-start-0" style="max-width: 60px;">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="col-12 col-md-3 d-flex justify-content-center">
            <a href="{{ route('video.crear.formulario') }}" class="btn btn-primary shadow-sm d-flex align-items-center gap-2" style="width: auto; padding: 10px 20px;">
                <i class="fas fa-plus"></i> <span>Nuevo Video</span>
            </a>
        </div>
    </div>

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
                    <div class="card mb-3 video-card">
                        <div class="video-thumbnail position-relative">
                            <a href="{{ route('video.detalle', ['id' => $video->id]) }}">
                                @if ($video->miniatura)
                                    <img src="{{ $video->miniatura }}" alt="Miniatura del video">
                                @else
                                    <img src="{{ asset('img/video-default.png') }}" alt="Miniatura por defecto">
                                @endif
                            </a>
                            <div class="video-duration">
                                {{ floor($video->duracion / 60) }}:{{ str_pad($video->duracion % 60, 2, '0', STR_PAD_LEFT) }}
                            </div>
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
