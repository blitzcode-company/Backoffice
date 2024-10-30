@extends('layouts.app')

@section('content')
    <div class="titulo">Ajustes</div>

    <div class="container-ajuste ">
        <div class="titulos-ajustes">Tema</div>
        <div class="theme-switch-wrapper">
            <label class="theme-switch">
                <input type="checkbox" id="toggle-theme-switch">
                <span class="slider"></span>
            </label>
            <span id="theme-label">Modo Oscuro</span>
        </div>
    </div>
@endsection
