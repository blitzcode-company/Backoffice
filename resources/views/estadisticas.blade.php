@extends('layouts.app')

@section('content')
    <div class="stats-container">
        <h1 class="stats-title">Administración de Canales - Estadísticas</h1>
        
        <div class="stats-section">
            <div class="stats-box">
                <h2 class="stats-title">Usuarios</h2>
                <ul class="stats-list">
                    <li><a href="{{ route('charts.premium_users') }}">Usuarios Premium</a></li>
                    <li><a href="{{ route('charts.active_users') }}">Usuarios Activos</a></li>
                    <li><a href="{{ route('charts.user_channel') }}">Usuarios creadores de contenido</a></li>
                </ul>
            </div>

            <div class="stats-box">
                <h2 class="stats-title">Videos</h2>
                <ul class="stats-list">
                    <li><a href="{{ route('charts.videos_por_etiqueta') }}">Videos por etiquetas</a></li>
                    <li><a href="{{ route('charts.mas_visitados_por_mes') }}">Videos Más Visitados el Mes Pasado</a></li>
                    <li><a href="#">Videos Recientes</a></li>
                </ul>
            </div>

            <div class="stats-box">
                <h2 class="stats-title">Canales</h2>
                <ul class="stats-list">
                    <li><a href="#">Estadísticas de Canales</a></li>
                    <li><a href="#">Canales más Seguidos</a></li>
                    <li><a href="#">Canales Recomendados</a></li>
                </ul>
            </div>

            <div class="stats-box">
                <h2 class="stats-title">Otros</h2>
                <ul class="stats-list">
                    <li><a href="#">Otras Estadísticas</a></li>
                    <li><a href="#">Visitas Diarias</a></li>
                    <li><a href="#">Estadísticas Generales</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
