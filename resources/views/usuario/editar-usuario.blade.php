@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Actualizar cuenta de Usuario</span>
    </div>
    <div class="container container-card">
        <div class="justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        Editar Usuario - <span class="text-muted">Para cambiar la foto de perfil, haz clic sobre la
                            imagen.</span>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('usuario.editar', ['id' => $user->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="mx-auto position-relative">
                                    <label for="foto" style="cursor: pointer;">
                                        <div class="user-photo text-center mb-3">
                                            <img id="previewFoto"
                                                src="{{ $user->foto ? $user->foto : asset('img/default-user.png') }}"
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
                                            value="{{ $user->name }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Nueva Contraseña</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Correo Electrónico</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ $user->email }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha_de_nacimiento">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_de_nacimiento" id="fecha_de_nacimiento"
                                            class="form-control" value="{{ $user->fecha_de_nacimiento }}">
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="form-check">
                                        <input type="hidden" name="premium" value="0">
                                        <input class="form-check-input" type="checkbox" name="premium" id="premium"
                                            value="1" {{ $user->premium ? 'checked' : '' }}>
                                        <label class="form-check-label" for="premium">
                                            Premium
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>

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
