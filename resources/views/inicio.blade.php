@extends('layouts.app')

@section('content')
    <h1>Bienvenido al Dashboard</h1>
    @if (Auth::check())
        <p>Hola, {{ Auth::user()->name }}.</p>
    @else
        <p>Por favor, inicia sesión para acceder al contenido.</p>
    @endif
    <p>Contenido de la página de inicio.</p>
@endsection
