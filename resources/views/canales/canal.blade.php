@extends('layouts.app')

@section('content')
    <div class="container container-card" >
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
                            @if ($canal->user && $canal->user->foto)
                                <img src="{{ asset($canal->user->foto) }}" alt="Foto de perfil de {{ $canal->user->name }}"
                                    class="rounded-circle canal-foto-perfil">
                            @else
                                <img src="{{ asset('img/default-user.png') }}" alt="Foto de perfil por defecto"
                                    class="rounded-circle canal-foto-perfil">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="canal-info-box">
                            <div class="canal-info">
                                <h2>{{ $canal->nombre }}</h2>
                                <p><strong>ID Canal:</strong> {{ $canal->id }}</p>
                                <p><strong>Videos:</strong> {{ $canal->videos_count }}</p>
                                @if ($canal->user)
                                    <p><strong>Propietario:</strong> {{ $canal->user->name }}</p>
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
                <h3>Descripción del Canal</h3>
                <p>{{ $canal->descripcion }}</p>
            </div>
            <div class="card-footer text-center">
                <a href="#" class="btn-action" data-toggle="modal" data-target="#confirmDeleteModal">
                    <i class="fas fa-trash-alt"></i> Eliminar
                </a>
                <a href="{{ route('update.canal', ['id' => $canal->id]) }}" class="btn-action">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>

        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="modal-close">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres eliminar este canal?</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('eliminar.canal', ['id' => $canal->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
