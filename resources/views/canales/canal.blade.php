@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Información del Canal</span>
    </div>
    <div class="container container-card">
        <div class="navigation-buttons mb-4">
            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#suscribirModal">
                <i class="fas fa-user-plus"></i> Suscribir
            </button>
        </div>

        @if (session('warning'))
            <div class="alert alert-warning text-center m-0 mx-auto mb-2" style="max-width: 800px;">
                {{ session('warning') }}
            </div>
        @endif

        <div class="card">
            <div class="canal-photo-large">
                @if ($canal->portada)
                    <img src="{{ $canal->portada }}" alt="Portada de {{ $canal->nombre }}" class="img-fluid w-100">
                @else
                    <img src="{{ asset('img/cover-default.png') }}" alt="Portada por defecto" class="img-fluid w-100">
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="mb-3 canal-foto-perfil">
                            <a href="{{ route('usuario.detalle', ['id' => $canal->user->id]) }}"
                                title="Ir a usuario #{{ $canal->user->id }}">
                                @if ($canal->user && $canal->user->foto)
                                    <img src="{{ asset($canal->user->foto) }}"
                                        alt="Foto de perfil de {{ $canal->user->name }}"
                                        class="rounded-circle canal-foto-perfil">
                                @else
                                    <img src="{{ asset('img/default-user.png') }}" alt="Foto de perfil por defecto"
                                        class="rounded-circle canal-foto-perfil">
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="canal-info-box">
                            <div class="canal-info">
                                <h2>{{ $canal->nombre }}</h2>
                                <a href="{{ route('suscriptores.listar', ['id' => $canal->id]) }}"
                                    class="btn btn-primary btn-sm mb-3">
                                    <i class="fas fa-users"></i>
                                    <strong>Suscriptores:</strong> {{ $canal->suscriptores_count }}
                                </a>
                                <p><strong>ID Canal:</strong> {{ $canal->id }}</p>
                                <p><strong>Videos:</strong> {{ $canal->videos_count }}</p>

                                @if ($canal->user)
                                    <p><strong>Propietario:</strong> <a class="custom-link"
                                            href="{{ route('usuario.detalle', ['id' => $canal->user->id]) }}">{{ $canal->user->name }}
                                            <i class="fas fa-link"></i></a></p>
                                    <p><strong>ID Usuario:</strong> {{ $canal->user->id }}</p>
                                @else
                                    <p>No tiene propietario asignado.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <h3>Descripción</h3>
                <br>
                <p class="text-justify">{!! nl2br(e($canal->descripcion)) !!}</p>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('usuario.detalle', ['id' => $canal->user->id]) }}" class="btn btn-success btn-sm w-40">
                    <i class="fas fa-user"></i> Ir a perfil
                </a>
                <a href="{{ route('video.canal', ['id' => $canal->id]) }}" class="btn custom-edit-btn btn-sm w-40">
                    <i class="fas fa-video"></i> Videos
                </a>
                <a href="{{ route('canal.editar.formulario', ['id' => $canal->id]) }}" class="btn btn-warning btn-sm w-40">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="" class="btn btn-danger btn-sm w-40" data-bs-toggle="modal"
                    data-bs-target="#confirmDeleteModal-{{ $canal->id }}">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
            </div>
        </div>

        @include('modals.deleteChannelModal', ['canal' => $canal])
        @include('modals.suscribe-modal', ['canal' => $canal])
    </div>
@endsection
