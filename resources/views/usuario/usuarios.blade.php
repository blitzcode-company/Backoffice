@extends('layouts.app')

@section('content')
    <div class="titulo">Usuarios de Blitzvideo</div>
    <div class="row align-items-center mb-4 g-3">
        <div class="col-md-3 d-none d-md-block"></div>
        
        <div class="col-12 col-md-6 d-flex justify-content-center">
            <form action="{{ route('usuario.nombre') }}" method="GET" class="w-100" style="max-width: 600px;">
                <div class="input-group shadow-sm">
                    <input type="search" name="nombre" placeholder="Buscar usuario por nombre..." class="form-control search-bar border-end-0"
                        value="{{ request('nombre') }}" required aria-label="Buscar usuario">
                    <button type="submit" class="btn btn-info border-start-0" style="max-width: 60px;">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="col-12 col-md-3 d-flex justify-content-center">
            <a href="{{ route('usuario.crear.formulario') }}" class="btn btn-primary shadow-sm d-flex align-items-center gap-2" style="width: auto; padding: 10px 20px;">
                <i class="fas fa-user-plus"></i> <span>Nuevo Usuario</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-auto w-75 mb-4 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-center mb-4">
        {{ $users->links('vendor.pagination.pagination') }}
    </div>

    <div class="user-list-container">
        @if ($users->isEmpty())
            <div class="text-center p-5 border rounded user-id-container">
                <div class="mb-3 text-muted opacity-50">
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <p class="h5 text-muted">No hay usuarios disponibles.</p>
                @if(request('nombre'))
                    <a href="{{ route('usuario.listar') }}" class="btn btn-link text-decoration-none mt-2">Ver todos los usuarios</a>
                @endif
            </div>
        @else
            <ul class="user-list p-0">
                @foreach ($users as $user)
                    <li class="user-item d-flex flex-column flex-md-row align-items-center align-items-md-start p-3 p-md-4 gap-3">
             
                        <div class="user-photo flex-shrink-0 mb-3 mb-md-0">
                            <a href="{{ route('usuario.detalle', ['id' => $user->id]) }}" class="position-relative d-block">
                                <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}"
                                    alt="{{ $user->name }}" class="rounded-circle shadow-sm" style="width: 90px; height: 90px; object-fit: cover;">
                            </a>
                        </div>

            
                        <div class="user-info flex-grow-1 w-100">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-start mb-2">
                                <div class="text-center text-md-start">
                                    <h2 class="mb-1 fw-bold h3">
                                        <a href="{{ route('usuario.detalle', ['id' => $user->id]) }}" class="text-decoration-none">
                                            {{ $user->name }}
                                        </a>
                                    </h2>
                                    <p class="text-muted mb-2"><i class="far fa-envelope me-1"></i>{{ $user->email }}</p>
                                </div>
                                
                   
                                <div class="user-id-container rounded-pill px-3 py-1 border d-flex align-items-center gap-2 mt-2 mt-md-0 shadow-sm">
                                    <span class="fw-bold id-label small">ID: {{ $user->id }}</span>
                                    <div class="vr opacity-25" style="height: 1.2em;"></div>
                                    <button class="btn btn-sm btn-link p-0 text-decoration-none copy-btn" data-copy="{{ $user->id }}" title="Copiar ID" style="color: inherit;">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <hr class="my-2 opacity-10">

        
                            <div class="d-flex flex-wrap justify-content-center justify-content-md-between align-items-center gap-3">
                                <div class="status-badges d-flex gap-2 flex-wrap justify-content-center">
                                    @if ($user->premium)
                                        <span class="badge bg-warning text-dark rounded-pill"><i class="fas fa-crown me-1"></i> Premium</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill"><i class="fas fa-user me-1"></i> Estándar</span>
                                    @endif
                                
                                    @if ($user->bloqueado)
                                        <span class="badge bg-danger rounded-pill"><i class="fas fa-ban me-1"></i> Bloqueado</span>
                                    @else
                                        <span class="badge bg-success rounded-pill"><i class="fas fa-check-circle me-1"></i> Activo</span>
                                    @endif
                                </div>

                                <div class="channel-info d-flex align-items-center flex-wrap gap-2">
                                    @if ($user->canales->isNotEmpty())
                                        @foreach ($user->canales as $canal)
                                            <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-2">
                                                <i class="fab fa-youtube"></i> 
                                                <span>{{ $canal->nombre }}</span>
                                                <i class="fas fa-chevron-right small opacity-50"></i>
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted small fst-italic">Sin canal</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('vendor.pagination.pagination') }}
    </div>
    <script src="{{ asset('js/copyButton.js') }}"></script>
@endsection
