@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Editar Stream</span>
    </div>
    <div class="container-card-video">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-video">
                    <div class="card-header">
                        Editar Stream - <span class="text-muted">Haz clic sobre la miniatura para cambiarla</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('stream.editar', ['id' => $stream->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-md-8 mx-auto">
                                <div class="form-group">
                                    <label for="miniatura">Miniatura del Stream</label>
                                    <label for="miniatura" class="video-thumbnail-large text-center mb-3"
                                        style="cursor: pointer;">
                                        <img id="previewMiniatura" src="{{ $stream->miniatura ?? asset('img/video-default.png') }}"
                                            alt="Miniatura del stream">
                                        <input type="file" name="miniatura" id="miniatura"
                                            class="form-control-file d-none" onchange="previewImage(this)">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="titulo">Título del Stream</label>
                                    <input type="text" name="titulo" id="titulo"
                                        class="form-control custom-input" required
                                        placeholder="Ingrese título del stream" value="{{ $stream->titulo }}">
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción del Stream</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control custom-textarea" rows="6" required
                                        placeholder="Ingrese descripción del stream">{{ $stream->descripcion }}</textarea>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-3">Guardar Cambios</button>
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
