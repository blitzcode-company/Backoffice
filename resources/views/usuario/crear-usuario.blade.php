@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Registro de Usuario</span>
    </div>
    <div class="container container-card">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Nuevo Usuario - <span class="text-muted">Para cambiar la foto de perfil, haz clic sobre la
                            imagen.</span>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('usuario.crear') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="mx-auto position-relative">
                                    <label for="foto" style="cursor: pointer;">
                                        <div class="user-photo text-center mb-3">
                                            <img id="previewFoto" src="{{ asset('img/default-user.png') }}"
                                                class="img-thumbnail" style="width: 200px; height: 200px;">
                                            <span class="edit-icon">
                                                <i class="fas fa-camera"></i>
                                            </span>
                                        </div>
                                        <input type="file" name="foto" id="foto" class="form-control-file d-none"
                                            onchange="previewImage(this)">
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ old('name') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Contraseña</label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Correo Electrónico</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email') }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Confirmar Contraseña</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha_de_nacimiento">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_de_nacimiento" id="fecha_de_nacimiento"
                                            class="form-control" value="{{ old('fecha_de_nacimiento') }}">
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="premium" id="premium">
                                        <label class="form-check-label" for="premium">
                                            Premium
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Crear Usuario</button>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#previewFoto').on('click', function() {
            $('#foto').click();
        });

        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#previewFoto').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
