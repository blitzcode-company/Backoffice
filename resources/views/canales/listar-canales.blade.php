@extends('layouts.app')

@section('content')
    <div class="search-container">
        <form action="{{ route('canal.nombre') }}" method="GET">
            <input type="search" name="nombre" placeholder="Buscar canal por nombre" id="nombre" class="search-bar">
            <button type="submit" class="btn-info"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="text-center my-4">
        <a href="{{ route('canal.crear.formulario') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Canal
        </a>
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
        @if ($canales->isEmpty())
            <p>No hay canales disponibles.</p>
        @else
            @foreach ($canales as $canal)
                <div class="card mb-3">
                    @if ($canal->portada)
                        <div class="canal-portada">
                            <img src="{{ $canal->portada }}" class="card-img-top" alt="Portada de {{ $canal->nombre }}">
                        </div>
                    @else
                        <div class="canal-portada">
                            <img src="{{ asset('img/cover-default.png') }}" class="card-img-top" alt="Portada por defecto">
                        </div>
                    @endif

                    <div class="canal-info-box">
                        <div class="canal-foto-perfil-list text-center ">
                            <img src="{{ $canal->user->foto ? asset($canal->user->foto) : asset('img/default-user.png') }}"
                                alt="Foto de {{ $canal->user->name }}" class="rounded-circle"
                                style="width: 150px; height: 150px;">
                        </div>

                        <div class="card-body">
                            <h2 class="card-title text-center" style="margin-top:60px;">{{ $canal->nombre }}</h2>
                            <p class="card-text">ID Canal: {{ $canal->id }}</p>
                            <p class="card-text">Videos: {{ $canal->videos_count }}</p>
                            @if ($canal->user)
                                <div class="canal-user mb-3">
                                    <p>Propietario: {{ $canal->user->name }}</p>
                                    <p>ID: {{ $canal->user->id }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer text-center">
                            <form action="{{ route('canal.detalle', ['id' => $canal->id]) }}" method="get">
                                <button type="submit" class="btn btn-primary btn-sm w-40">
                                    <i class="fas fa-info-circle"></i> Ver Detalles
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="d-flex justify-content-center">
        {{ $canales->links('vendor.pagination.pagination') }}
    </div>
@endsection
