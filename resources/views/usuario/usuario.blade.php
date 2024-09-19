@extends('layouts.app')

@section('content')
    <div class="user-details-container card">
        <div class="user-photo-large">
            <img src="{{ $user->foto ? asset($user->foto) : asset('img/default-user.png') }}" alt="{{ $user->name }}">
        </div>
        <div class="user-info-box">
            <div class="user-info">
                <h2>{{ $user->name }}(#{{ $user->id }})</h2>
                <p><strong>Inició el:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Premium:</strong> {{ $user->premium ? 'Sí' : 'No' }}</p>

                @if ($user->canales->isNotEmpty())
                    @foreach ($user->canales as $canal)
                        <p>
                            <p><strong>Canal:</strong>
                            <a href="{{ route('canal.detalle', ['id' => $canal->id]) }}">
                                {{ $canal->nombre }}
                                <i class="fas fa-link"></i>
                            </a>
                        </p>

                        <div class="user-canal-info-box">
                            <p class="text-justify">{!! nl2br(e($canal->descripcion)) !!}</p>
                        </div>
                    @endforeach
                @else
                    <p>No tiene canal asociado.</p>
                @endif

            </div>
        </div>
        <div class="modal-footer">
            <div class="mx-auto">
                <a href="" class="btn btn-success btn-sm w-40">
                    <i class="fas fa-envelope"></i> Enviar Correo
                </a>
                <a href="" class="btn btn-danger btn-sm w-40" data-toggle="modal" data-target="#confirmDeleteModal">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
                <a href="{{ route('usuario.editar.formulario', ['id' => $user->id]) }}"
                    class="btn btn-warning btn-sm w-40">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
    </div>

    @include('modals.delete-user-modal', ['user' => $user])
@endsection
