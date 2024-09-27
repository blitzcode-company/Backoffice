@extends('layouts.app')

@section('content')
<div class="titulo">Gestión de usuarios del Sistema</div>
    <div class="container-admin">
        <div class="d-flex justify-content-center">
            {{ $usuarios->links('vendor.pagination.pagination') }}
        </div>

        <div class="row">
            @foreach ($usuarios as $usuario)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title my-0">{{ $usuario->name }}</h5>
                        </div>
                        <div class="card-body text-left">
                            <p class="card-text">
                                <strong>ID:</strong> {{ $usuario->id }} <br>
                                <strong>Username:</strong> {{ $usuario->username }} <br>
                                <strong>Email:</strong> {{ $usuario->email }} <br>
                                <strong>Dominio:</strong> {{ $usuario->domain }} <br>
                                <strong>Creado:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }} <br>
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.actividades', ['id' => $usuario->id]) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-history"></i> Actividad
                            </a>
                            <a href="#" class="btn btn-secondary btn-sm">
                                <i class="fas fa-user-shield"></i> Permisos
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center">
            {{ $usuarios->links('vendor.pagination.pagination') }}
        </div>
    </div>
@endsection
