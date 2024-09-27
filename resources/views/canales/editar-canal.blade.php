@extends('layouts.app')

@section('content')
<div class="titulo">Editar infromación del Canal</div>
    <div class="container container-card">
    
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    Editar Canal - <span class="text-muted">Para cambiar la foto de portada, haz clic sobre la imagen.</span>
                </div>

                <div class="card-body" style="padding: 0;">
                    <form action="{{ route('canal.editar', $canal->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <label for="portada">
                            <div class="canal-photo-large text-center mb-3 position-relative">
                                <img id="previewPortada"
                                    src="{{ $canal->portada ? asset($canal->portada) : asset('img/cover-default.png') }}"
                                    class="img-fluid">
                                <img src="{{ asset('img/camara.png') }}" alt="Camara" class="position-absolute camara-icon">
                            </div>
                            <input type="file" name="portada" id="portada" class="form-control-file d-none"
                                onchange="previewImage(this)">
                        </label>

                        <div class="form-group">
                            <label for="nombre">Nombre del Canal</label>
                            <input type="text" name="nombre" id="nombre" class="form-control custom-input" required
                                placeholder="Ingrese nombre del canal" value="{{ $canal->nombre }}">
                        </div>

                        <div class="form-group">
                            <label for="descripcion">Descripción del Canal</label>
                            <textarea name="descripcion" id="descripcion" class="form-control custom-textarea" rows="6" required
                                placeholder="Ingrese descripción del canal">{{ $canal->descripcion }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary my-5">Actualizar Canal</button>

                        @if ($errors->any())
                            <div class="alert alert-danger w-50 mx-auto mb-10 mt-0">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success w-50 mx-auto mb-10 mt-0">
                                {{ session('success') }}
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#previewPortada').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
