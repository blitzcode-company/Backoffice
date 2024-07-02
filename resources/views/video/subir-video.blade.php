@extends('layouts.app')

@section('content')
    <div class="container-card-video">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-video">
                    <div class="card-header">Subir Nuevo Video</div>

                    <div class="card-body">
                        <form action="{{ route('videos.subir') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="miniatura">Miniatura del Video</label>
                                        <label for="miniatura" class="video-thumbnail-large text-center mb-3" style="cursor: pointer;">
                                            <img id="previewMiniatura" src="{{ asset('img/video-default.png') }}" alt="Miniatura del video">
                                            <input type="file" name="miniatura" id="miniatura" class="form-control-file d-none" onchange="previewImage(this)">
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label for="canal_id">ID del Canal</label>
                                        <input type="text" name="canal_id" id="canal_id" class="form-control custom-input" required placeholder="Ingrese ID del canal">
                                    </div>

                                    <div class="form-group">
                                        <label for="titulo">Título del Video</label>
                                        <input type="text" name="titulo" id="titulo" class="form-control custom-input" required placeholder="Ingrese título del video">
                                    </div>

                                    <div class="form-group">
                                        <label for="descripcion">Descripción del Video</label>
                                        <textarea name="descripcion" id="descripcion" class="form-control custom-textarea" rows="6" required placeholder="Ingrese descripción del video"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="video">Video</label>
                                        <input type="file" name="video" id="video" class="form-control-file" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="etiquetas">Etiquetas</label>
                                        <div class="etiquetas-list">
                                            @foreach ($etiquetas as $etiqueta)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="etiqueta_{{ $etiqueta->id }}" name="etiquetas[]" value="{{ $etiqueta->id }}">
                                                    <label class="form-check-label" for="etiqueta_{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-3">Subir Video</button>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
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
