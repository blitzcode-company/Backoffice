@extends('layouts.app')

@section('content')
    <div class="titulo">Canales de Blitzvideo</div>
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-3 d-none d-md-block"></div>
        
        <div class="col-12 col-md-6 d-flex justify-content-center">
            <form action="{{ route('canal.nombre') }}" method="GET" class="w-100" style="max-width: 600px;">
                <div class="input-group shadow-sm">
                    <input type="search" name="nombre" placeholder="Buscar canal por nombre..." class="form-control search-bar border-end-0"
                        value="{{ request('nombre') }}" id="nombre" aria-label="Buscar canal">
                    <button type="submit" class="btn btn-info border-start-0" style="max-width: 60px;">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="col-12 col-md-3 d-flex justify-content-center">
            <a href="{{ route('canal.crear.formulario') }}" class="btn btn-primary shadow-sm d-flex align-items-center gap-2" style="width: auto; padding: 10px 20px;">
                <i class="fas fa-plus"></i> <span>Nuevo Canal</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success text-center mx-auto mt-0" style="max-width: 800px; margin-top: 0 !important;">
            {{ session('success') }}
        </div>
    @endif
    <div class="d-flex justify-content-center">
        {{ $canales->links('vendor.pagination.pagination') }}
    </div>
    <div class="canal-list-container mx-5">
        <div class="canal-list">
            @if ($canales->isEmpty())
                <p>No hay canales disponibles.</p>
            @else
                @foreach ($canales as $canal)
                    <div class="card mb-3">
                        <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}">
                            @if ($canal->portada)
                                <div class="canal-portada">
                                    <img src="{{ $canal->portada }}" class="card-img-top"
                                        alt="Portada de {{ $canal->nombre }}">
                                </div>
                            @else
                                <div class="canal-portada">
                                    <img src="{{ asset('img/cover-default.png') }}" class="card-img-top"
                                        alt="Portada por defecto">
                                </div>
                            @endif
                        </a>
                        <div class="canal-info-box">
                            <div class="canal-foto-perfil-list text-center ">
                                <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}">
                                    <img src="{{ $canal->user->foto ? asset($canal->user->foto) : asset('img/default-user.png') }}"
                                        alt="Foto de {{ $canal->user->name }}" class="rounded-circle"
                                        style="width: 150px; height: 150px;">
                                </a>
                            </div>

                            <div class="card-body">
                                <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}">
                                    <h2 class="card-title text-center mb-4" style="margin-top:60px;">{{ $canal->nombre }}
                                    </h2>
                                </a>
                                <p class="d-flex align-items-center m-0">
                                    <span class="h4 m-0 button-separator">#{{ $canal->id }}</span>
                                    <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}" class="button-info"
                                        title="Ir a canal #{{ $canal->id }}">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <button class="btn btn-secondary btn-sm copy-btn" data-copy="{{ $canal->id }}"
                                        title="Copiar ID">
                                        <i class="fas fa-copy copy-icon"></i>
                                    </button>
                                    <span class="copy-status text-muted ml-2">Copiar</span>
                                </p>

                                <i class="fas fa-video text-muted"></i> Videos: {{ $canal->videos_count }}
                                | <i class="fas fa-user text-muted"></i> Suscriptores: {{ $canal->suscriptores_count }}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="d-flex justify-content-center">
        {{ $canales->links('vendor.pagination.pagination') }}
    </div>
    <script src="{{ asset('js/copyButton.js') }}"></script>
@endsection
