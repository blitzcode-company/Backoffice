<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blitzvideo - Backoffice</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="{{ route('inicio') }}">
            <img src="{{ asset('img/favicon.png') }}" alt="Logo" class="logo-navbar">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('inicio') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('perfil') }}">Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ajustes') }}">Configuración</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">Menú</div>
            <div class="list-group list-group-flush">
                <a href="{{ route('inicio') }}" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="{{ route('usuarios') }}" class="list-group-item list-group-item-action">Usuarios</a>
                <a href="{{ route('listar.canales') }}" class="list-group-item list-group-item-action">Canales</a>
                <a href="{{ route('videos') }}" class="list-group-item list-group-item-action">Videos</a>
                <a href="{{ route('publicidades') }}" class="list-group-item list-group-item-action">Publicidad</a>
                <a href="{{ route('estadisticas') }}" class="list-group-item list-group-item-action">Estadísticas</a>
                <a href="{{ route('anuncios') }}" class="list-group-item list-group-item-action">Anuncios</a>
                <a href="{{ route('ajustes') }}" class="list-group-item list-group-item-action">Ajustes</a>
            </div>
        </div>

        <div id="page-content-wrapper" class="flex-grow-1 p-3">
            @yield('content')
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
