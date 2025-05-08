<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blitzvideo - Backoffice</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables-custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="{{ route('inicio') }}">
            <img src="{{ asset('img/favicon.png') }}" alt="Logo" class="logo-navbar">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarMenu" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bars"></i> Menú
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarMenu">
                            <a href="{{ route('inicio') }}" class="dropdown-item">Dashboard</a>
                            <a href="{{ route('usuario.listar') }}" class="dropdown-item">Usuarios</a>
                            <a href="{{ route('transaccion.filtrar') }}" class="dropdown-item">Transacciones</a>
                            <a href="{{ route('canal.listar') }}" class="dropdown-item">Canales</a>

                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#"
                                    data-toggle="dropdown">Videos</a>
                                <div class="dropdown-menu">
                                    <a href="{{ route('video.listar') }}" class="dropdown-item">Todos</a>
                                    <a href="{{ route('video.crear') }}" class="dropdown-item">Subir Video</a>
                                    <a href="{{ route('video.etiquetas') }}" class="dropdown-item">Por etiquetas</a>
                                </div>
                            </div>
                            <a href="{{ route('stream.streams') }}" class="dropdown-item">Stream</a>
                            <a href="{{ route('playlists.listar') }}" class="dropdown-item">Playlist</a>
                            <a href="{{ route('etiquetas.listar') }}" class="dropdown-item">Etiquetas</a>
                            <a href="{{ route('estadisticas') }}" class="dropdown-item">Estadísticas</a>
                            <a href="{{ route('publicidad.listar') }}" class="dropdown-item">Publicidad</a>
                            <a href="{{ route('ajustes') }}" class="dropdown-item">Ajustes</a>
                        </div>


                    </li>
                    @if (Auth::check())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->username }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('perfil') }}">Mi Perfil</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">Cerrar Sesión</a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <div id="sidebar-wrapper" class="d-none d-lg-block">
            <div class="sidebar-heading">Menú</div>
            <div class="list-group list-group-flush">
                <a href="{{ route('inicio') }}" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="{{ route('usuario.listar') }}" class="list-group-item list-group-item-action">Usuarios</a>
                <a href="{{ route('transaccion.filtrar') }}"
                    class="list-group-item list-group-item-action">Transacciones</a>
                <a href="{{ route('canal.listar') }}" class="list-group-item list-group-item-action">Canales</a>

                <div class="accordion accordion-custom" id="videosAccordion">
                    <div class="">
                        <button
                            class="accordion-button accordion-button-custom d-flex justify-content-between align-items-center"
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                            aria-expanded="false" aria-controls="collapseOne">
                            <span>Videos</span>
                            <i class="fas fa-chevron-down" id="toggle-icon"></i>
                        </button>

                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#videosAccordion">
                            <div class="accordion-body accordion-body-custom">
                                <a href="{{ route('video.crear') }}"
                                    class="list-group-item list-group-item-action list-group-item-custom">
                                    <i class="fas fa-plus"></i> Subir video
                                </a>

                                <a href="{{ route('video.listar') }}"
                                    class="list-group-item list-group-item-action list-group-item-custom">
                                    <i class="fas fa-list"></i> Todos
                                </a>

                                <a href="{{ route('video.etiquetas') }}"
                                    class="list-group-item list-group-item-action list-group-item-custom">
                                    <i class="fas fa-tags"></i> Por etiquetas
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('stream.streams') }}" class="list-group-item list-group-item-action">Stream</a>
                <a href="{{ route('playlists.listar') }}" class="list-group-item list-group-item-action">Playlist</a>
                <a href="{{ route('etiquetas.listar') }}"
                    class="list-group-item list-group-item-action">Etiquetas</a>
                <a href="{{ route('estadisticas') }}" class="list-group-item list-group-item-action">Estadísticas</a>
                <a href="{{ route('publicidad.listar') }}" class="list-group-item list-group-item-action">Publicidad</a>
                <a href="{{ route('ajustes') }}" class="list-group-item list-group-item-action">Ajustes</a>
            </div>
        </div>

        <div id="page-content-wrapper" class="flex-grow-1 p-3">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('js/darkmode.js') }}"></script>
    <script src="{{ asset('js/dropdown.js') }}"></script>
    <script src="{{ asset('js/accordion-button.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
</body>

</html>
