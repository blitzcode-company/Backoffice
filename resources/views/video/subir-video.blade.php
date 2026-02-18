@extends('layouts.app')

@section('content')
    <div class="admin-video-container">
        {{-- Header --}}
        <div class="page-header">
            <div class="d-flex align-items-center gap-3">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2>Subir Nuevo Video</h2>
            </div>
        </div>

        {{-- Alertas --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('video.crear') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Columna Principal --}}
                <div class="col-lg-8">
                    <div class="content-section">
                        <div class="mb-4">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" name="titulo" id="titulo" class="form-control form-control-lg" required placeholder="Escribe un título llamativo">
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="8" required placeholder="Cuéntales a tus espectadores sobre tu video"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="canal_id" class="form-label">ID del Canal</label>
                                <input type="text" name="canal_id" id="canal_id" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="acceso" class="form-label">Visibilidad</label>
                                <select name="acceso" id="acceso" class="form-select" required>
                                    <option value="publico">Público</option>
                                    <option value="privado">Privado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Columna Lateral --}}
                <div class="col-lg-4">
                    <div class="content-section">
                        <div class="mb-4">
                            <label class="form-label">Archivo de Video</label>
                            <input type="file" name="video" id="video" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Miniatura</label>
                            <label for="miniatura" class="upload-zone d-block">
                                <img id="previewMiniatura" src="{{ asset('img/video-default.png') }}" alt="Vista previa" class="mb-2">
                                <div class="text-muted small">Click para subir imagen</div>
                                <input type="file" name="miniatura" id="miniatura" class="d-none" onchange="previewImage(this)">
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Etiquetas</label>
                            <div class="border rounded p-3 tags-box" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($etiquetas as $etiqueta)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="etiqueta_{{ $etiqueta->id }}" name="etiquetas[]" value="{{ $etiqueta->id }}">
                                        <label class="form-check-label" for="etiqueta_{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Publicar Video</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#previewMiniatura').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    
@endsection
