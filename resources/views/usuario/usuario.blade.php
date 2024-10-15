@extends('layouts.app')

@section('content')
    <div class="titulo">Información de Usuario</div>
    <div class="user-details-container card">
        <div class="user-photo-large">
            <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}" alt="{{ $user->name }}">
        </div>
        <div class="user-info-box">
            <div class="user-info">
                <h2>{{ $user->name }}(#{{ $user->id }})</h2>
                <p><strong>Inició el:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                
                <!-- Fecha de nacimiento y edad -->
                @if ($user->fecha_de_nacimiento)
                    <p><strong>Fecha de Nacimiento:</strong> {{ \Carbon\Carbon::parse($user->fecha_de_nacimiento)->format('d/m/Y') }}</p>
                    <p><strong>Edad:</strong> {{ \Carbon\Carbon::parse($user->fecha_de_nacimiento)->age }} años</p>
                @else
                    <p><strong>Fecha de Nacimiento:</strong> No disponible</p>
                @endif

                <div class="email-container">
                    <p>
                        <i class="fas fa-envelope"></i>
                        <span class="button-separator">{{ $user->email }}</span>
                        <button class="btn btn-secondary btn-sm copy-btn" data-copy="{{ $user->email }}">
                            <i class="fas fa-copy copy-icon"></i>
                        </button>
                        <span class="copy-status">Copiar</span>
                    </p>
                </div>
                <p><strong>Premium:</strong>
                    @if ($user->premium)
                        <i class="fas fa-check" style="color: green;"></i>
                    @else
                        <i class="fas fa-times" style="color: red;"></i>
                    @endif
                </p>

                @if ($user->canales->isNotEmpty())
                    @foreach ($user->canales as $canal)
                        <p>
                            <strong>Canal:</strong>
                            <a class="custom-link" href="{{ route('canal.detalle', ['id' => $canal->id]) }}">
                                {{ $canal->nombre }} <i class="fas fa-link"></i>
                            </a>
                        </p>
                        <div class="user-canal-info-box">
                            <p class="text-justify">{!! nl2br(e($canal->descripcion)) !!}</p>
                        </div>
                        <br>
                    @endforeach
                @else
                    <p>No tiene canal asociado.</p>
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <div class="mx-auto">
                <a href="#" class="btn btn-success btn-sm w-40" data-bs-toggle="modal" data-bs-target="#sendEmailModal">
                    <i class="fas fa-envelope"></i> Enviar Correo
                </a>
                <a href="#" class="btn btn-danger btn-sm w-40" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
                
                <a href="{{ route('usuario.editar.formulario', ['id' => $user->id]) }}" class="btn btn-warning btn-sm w-40">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success w-50 text-center mx-auto">
            {{ session('success') }}
        </div>
    @endif

    @include('modals.delete-user-modal', ['user' => $user])
    @include('modals.send-email-modal', [
        'user' => $user,
        'ruta' => route('usuario.detalle', ['id' => $user->id]),
    ])
    <script src="{{ asset('js/copyButton.js') }}"></script>
@endsection
