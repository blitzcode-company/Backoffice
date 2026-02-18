@extends('layouts.app')

@section('content')
    <div class="container container-card mt-4">
        <div class="d-flex align-items-center mb-4">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm me-3 rounded-circle shadow-sm" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="titulo mb-0 border-0 p-0" style="font-size: 1.75rem;">Información del Canal</h2>
        </div>

        @if (session('warning'))
            <div class="alert alert-warning text-center mb-4">
                {{ session('warning') }}
            </div>
        @endif

        <div class="channel-card">
            <div class="channel-cover">
                @if ($canal->portada)
                    <img src="{{ $canal->portada }}" alt="Portada de {{ $canal->nombre }}">
                @else
                    <img src="{{ asset('img/cover-default.png') }}" alt="Portada por defecto">
                @endif
            </div>

            <div class="channel-header-content">
                <div class="row">
                    <div class="col-md-auto text-center text-md-start">
                        <div class="channel-avatar-container">
                            <a href="{{ route('usuario.detalle', ['id' => $canal->user->id]) }}">
                                @if ($canal->user && $canal->user->foto)
                                    <img src="{{ asset($canal->user->foto) }}" alt="{{ $canal->user->name }}" class="channel-avatar">
                                @else
                                    <img src="{{ asset('img/default-user.png') }}" alt="User" class="channel-avatar">
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="channel-details pt-md-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-start">
                                <div class="text-center text-md-start mb-3 mb-md-0">
                                    <h1 class="channel-title">{{ $canal->nombre }}</h1>
                                    <div class="channel-stats">
                                        <a href="{{ route('suscriptores.listar', ['id' => $canal->id]) }}" class="stat-item text-decoration-none" title="Ver Suscriptores">
                                            <i class="fas fa-users"></i> {{ $canal->suscriptores_count }} Suscriptores
                                        </a>
                                        <span class="stat-item" title="Videos">
                                            <i class="fas fa-video"></i> {{ $canal->videos_count }} Videos
                                        </span>
                                        <span class="stat-item" title="ID Canal">
                                            <i class="fas fa-hashtag"></i> {{ $canal->id }}
                                        </span>
                                    </div>
                                    @if ($canal->user)
                                        <div class="channel-owner text-muted small">
                                            Propietario: <a href="{{ route('usuario.detalle', ['id' => $canal->user->id]) }}" class="text-decoration-none fw-bold">{{ $canal->user->name }}</a>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="channel-actions-bar">
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#suscribirModal">
                                        <i class="fas fa-bell"></i> Suscribir
                                    </button>
                                    <a href="{{ route('video.canal', ['id' => $canal->id]) }}" class="btn btn-outline-primary" title="Ver Videos">
                                        <i class="fas fa-video"></i> Videos
                                    </a>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="channelOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="channelOptions">
                                            <li><a class="dropdown-item" href="{{ route('canal.editar.formulario', ['id' => $canal->id]) }}"><i class="fas fa-edit me-2"></i> Editar</a></li>
                                            <li><a class="dropdown-item" href="{{ route('usuario.detalle', ['id' => $canal->user->id]) }}"><i class="fas fa-user me-2"></i> Ver Perfil</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-{{ $canal->id }}"><i class="fas fa-trash-alt me-2"></i> Eliminar</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="channel-description-section">
                <h3>Descripción</h3>
                <div class="description-content">
                    {!! nl2br(e($canal->descripcion)) !!}
                </div>
            </div>
        </div>

        @include('modals.deleteChannelModal', ['canal' => $canal])
        @include('modals.suscribe-modal', ['canal' => $canal])
    </div>
@endsection
