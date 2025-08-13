@extends('layouts.app')

@section('content')
    <div class="titulo">Administración de Estadísticas</div>
    <div class="stats-container">
        <div class="stats-section">
            <div class="stats-box">
                <h2 class="stats-title">Usuarios</h2>
                <ul class="stats-list">
                    <li><a href="{{ route('charts.premium_users') }}">Usuarios Premium</a></li>
                    <li><a href="{{ route('charts.active_users') }}">Usuarios Activos</a></li>
                    <li><a href="{{ route('charts.user_channel') }}">Usuarios creadores de contenido</a></li>
                    <li><a href="{{ route('charts.commenting_liking_users') }}">Usuarios que Comentan/Dan Me Gusta</a></li>
                    <li><a href="{{ route('charts.monthly_registrations') }}">Usuarios Registrados por Mes</a></li>
                    <li><a href="{{ route('charts.blocked_active_users') }}">Usuarios Bloqueados vs. Activos</a></li>
                </ul>
            </div>

            <div class="stats-box">
                <h2 class="stats-title">Videos</h2>
                <ul class="stats-list">
                    <li><a href="{{ route('charts.videos_por_etiqueta') }}">Videos por etiquetas</a></li>
                    <li><a href="{{ route('charts.mas_visitados_por_mes') }}">Videos Más Visitados el Mes Pasado</a></li>
                    <li><a href="{{ route('charts.video_status') }}">Videos Activos vs. Bloqueados</a></li>
                    <li><a href="{{ route('charts.video_access_level') }}">Videos por Nivel de Acceso</a></li>
                    <li><a href="{{ route('charts.top_commented_videos') }}">Videos con Más Comentarios</a></li>
                    <li><a href="{{ route('charts.video_rating_distribution') }}">Distribución de Puntuaciones de
                            Videos</a></li>
                    <li><a href="{{ route('charts.video_ads') }}">Videos con Publicidad y Vistas</a></li>
                </ul>
            </div>

            <div class="stats-box">
                <h2 class="stats-title">Canales</h2>
                <ul class="stats-list">
                    <li><a href="{{ route('charts.top_followed_channels') }}">Canales más Seguidos</a></li>
                    <li><a href="{{ route('charts.channels_by_video_count') }}">Canales por Cantidad de Videos</a></li>
                    <li><a href="{{ route('charts.channel_stream_status') }}">Canales con Streams Activos vs. Inactivos</a>
                    <li><a href="{{ route('charts.channel_creation_date') }}">Canales por Antigüedad (Creación)</a></li>
                    </li>
                </ul>
            </div>

            <div class="stats-box">
                <h2 class="stats-title">Otros</h2>
                <ul class="stats-list">
                    <li><a href="{{ route('charts.content_reports') }}">Reportes de Contenido por Categoría</a></li>
                    <li><a href="{{ route('charts.notification_status') }}">Uso de Notificaciones (Leídas vs. No Leídas)</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
