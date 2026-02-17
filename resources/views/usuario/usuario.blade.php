@extends('layouts.app')

@section('content')
    <div class="container-xl">
        {{-- Header --}}
        <div class="d-flex align-items-center mb-4">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm me-3 rounded-circle shadow-sm" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="titulo mb-0 border-0 p-0" style="font-size: 1.75rem;">Información de Usuario</h2>
        </div>

        <div class="row g-4">
            {{-- Left Column: Profile --}}
            <div class="col-lg-4 col-xl-3">
                <div class="card border-0 shadow-sm h-100 profile-card">
                    <div class="card-body text-center p-4">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}" 
                                alt="{{ $user->name }}" 
                                class="rounded-circle img-thumbnail shadow-sm" 
                                style="width: 160px; height: 160px; object-fit: cover;">
                            @if($user->premium)
                                <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-warning text-dark border border-white shadow-sm p-2" title="Usuario Premium">
                                    <i class="fas fa-crown"></i>
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                        
                        <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                            <div class="user-id-container rounded-pill px-3 py-1 border d-flex align-items-center gap-2">
                                <span class="fw-bold id-label small">ID: {{ $user->id }}</span>
                                <div class="vr opacity-25" style="height: 1em;"></div>
                                <button class="btn btn-sm btn-link p-0 text-decoration-none copy-btn" data-copy="{{ $user->id }}" title="Copiar ID" style="color: inherit;">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-2 mb-4 flex-wrap">
                            @if ($user->bloqueado)
                                <span class="badge bg-danger rounded-pill"><i class="fas fa-ban me-1"></i> Bloqueado</span>
                            @else
                                <span class="badge bg-success rounded-pill"><i class="fas fa-check-circle me-1"></i> Activo</span>
                            @endif
                            
                            @if ($user->premium)
                                <span class="badge bg-warning text-dark rounded-pill"><i class="fas fa-crown me-1"></i> Premium</span>
                            @else
                                <span class="badge bg-secondary rounded-pill"><i class="fas fa-user me-1"></i> Estándar</span>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('usuario.editar.formulario', ['id' => $user->id]) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i> Editar Perfil
                            </a>
                            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#sendEmailModal">
                                <i class="fas fa-envelope me-2"></i> Enviar Correo
                            </button>
                            <a href="{{ route('playlists.usuario.listar', ['id' => $user->id]) }}" class="btn btn-outline-dark">
                                <i class="fas fa-th-list me-2"></i> Ver Playlists
                            </a>
                            <button class="btn {{ $user->bloqueado ? 'btn-secondary' : 'btn-warning' }}" data-bs-toggle="modal" data-bs-target="#confirmBlockModalUser{{ $user->id }}">
                                <i class="fas fa-ban me-2"></i> {{ $user->bloqueado ? 'Desbloquear' : 'Bloquear' }}
                            </button>
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                <i class="fas fa-trash-alt me-2"></i> Eliminar Usuario
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Details --}}
            <div class="col-lg-8 col-xl-9">
                {{-- Personal Data --}}
                <div class="card border-0 shadow-sm mb-4 info-card">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2 text-primary"></i>Datos Personales</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Correo Electrónico</label>
                                <div class="d-flex align-items-center">
                                    <span class="fw-medium fs-5 me-2">{{ $user->email }}</span>
                                    <button class="btn btn-sm btn-link p-0 text-muted copy-btn" data-copy="{{ $user->email }}" title="Copiar Email">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Fecha de Registro</label>
                                <div class="fw-medium fs-5">{{ $user->created_at->format('d/m/Y') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Fecha de Nacimiento</label>
                                <div class="fw-medium fs-5">
                                    @if ($user->fecha_de_nacimiento)
                                        {{ \Carbon\Carbon::parse($user->fecha_de_nacimiento)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted fst-italic small">No disponible</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Edad</label>
                                <div class="fw-medium fs-5">
                                    @if ($user->fecha_de_nacimiento)
                                        {{ \Carbon\Carbon::parse($user->fecha_de_nacimiento)->age }} años
                                    @else
                                        <span class="text-muted fst-italic small">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Channel Data --}}
                <div class="card border-0 shadow-sm info-card">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-tv me-2 text-primary"></i>Información del Canal</h5>
                    </div>
                    <div class="card-body p-4">
                        @forelse ($user->canales as $canal)
                            <div class="d-flex flex-column flex-md-row gap-4 align-items-start">
                                @if($canal->portada)
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}">
                                            <img src="{{ asset($canal->portada) }}" alt="Portada" class="rounded shadow-sm" style="width: 200px; height: 112px; object-fit: cover;">
                                        </a>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h4 class="fw-bold mb-2">
                                            <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}" class="text-decoration-none text-reset">
                                                {{ $canal->nombre }}
                                            </a>
                                        </h4>
                                        <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            Ver <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-0 text-break" style="white-space: pre-line;">
                                        {{ $canal->descripcion }}
                                    </p>
                                </div>
                            </div>
                            @if(!$loop->last) <hr class="my-4"> @endif
                        @empty
                            <div class="text-center py-4 text-muted opacity-50">
                                <i class="fab fa-youtube fa-3x mb-3"></i>
                                <p class="mb-0">Este usuario no tiene un canal asociado.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show align-items-center text-white bg-success border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @include('modals.delete-user-modal', ['user' => $user])
    @include('modals.send-email-modal', [
        'user' => $user,
        'ruta' => route('usuario.detalle', ['id' => $user->id]),
    ])
    @include('modals.blockModalUser', ['user' => $user])
    <script src="{{ asset('js/copyButton.js') }}"></script>
@endsection
