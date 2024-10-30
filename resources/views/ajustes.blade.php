@extends('layouts.app')

@section('content')
    <div class="titulo">Ajustes</div>

    <div class="container-ajuste ">
        <div class="ajustes-seccion">
            <div class="titulos-ajustes">Tema</div>
            <div class="theme-switch-wrapper">
                <label class="theme-switch">
                    <input type="checkbox" id="toggle-theme-switch">
                    <span class="slider"></span>
                </label>
                <span id="theme-label">Modo Oscuro</span><span class="text-muted"> | Reduce la fatiga visual y mejora la legibilidad en entornos oscuros.</span>
            </div>
        </div>
        <div class="ajustes-seccion">
            <div class="titulos-ajustes">Administrar Usuarios Backoffice</div>
            <p>
                <a href="{{ route('admin.usuarios') }}">Administrar Usuarios</a>
                <span class="text-muted"> | Gestiona la actividad y los permisos de los usuarios de backoffice.</span>
            </p>
        </div>
    </div>
@endsection
