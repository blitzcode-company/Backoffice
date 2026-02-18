@extends('layouts.app')

@section('content')
    <div class="container-xl">
        <div class="d-flex align-items-center mb-4">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm me-3 rounded-circle shadow-sm" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="titulo mb-0 border-0 p-0" style="font-size: 1.75rem;">Registro de Usuario</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm info-card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h5 class="card-title fw-bold">Nuevo Usuario</h5>
                            <p class="text-muted small">Completa la información para registrar un nuevo usuario.</p>
                        </div>

                        <form action="{{ route('usuario.crear') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Foto de Perfil --}}
                            <div class="d-flex justify-content-center mb-4">
                                <div class="position-relative">
                                    <label for="foto" class="cursor-pointer" style="cursor: pointer;" title="Cambiar foto de perfil">
                                        <img id="previewFoto" src="{{ asset('img/default-user.png') }}"
                                            class="rounded-circle shadow-sm object-fit-cover" 
                                            style="width: 150px; height: 150px; object-fit: cover;">
                                        <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-camera small"></i>
                                        </div>
                                    </label>
                                    <input type="file" name="foto" id="foto" class="d-none" onchange="previewImage(this)">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold small text-uppercase text-muted">Nombre</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Nombre completo">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold small text-uppercase text-muted">Correo Electrónico</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="ejemplo@correo.com">
                                </div>

                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold small text-uppercase text-muted">Contraseña</label>
                                    <input type="password" name="password" id="password" class="form-control" required placeholder="********">
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold small text-uppercase text-muted">Confirmar Contraseña</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="********">
                                </div>

                                <div class="col-md-6">
                                    <label for="fecha_de_nacimiento" class="form-label fw-bold small text-uppercase text-muted">Fecha de Nacimiento</label>
                                    <input type="date" name="fecha_de_nacimiento" id="fecha_de_nacimiento" class="form-control" value="{{ old('fecha_de_nacimiento') }}">
                                </div>

                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="premium" id="premium" value="1">
                                        <label class="form-check-label fw-bold" for="premium">Usuario Premium</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                    <i class="fas fa-save me-2"></i> Guardar
                                </button>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3 mb-0">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success mt-3 mb-0">
                                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
