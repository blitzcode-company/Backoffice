@extends('layouts.app')

@section('content')
    <div class="container container-card">
        <div class="row justify-content-center">

            <div class="card">
                <div class="card-header">Editar Canal</div>

                <div class="card-body" style="padding: 0;">
                    <form action="{{ route('update.canal', $canal->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <label for="portada">
                            <div class="canal-photo-large text-center mb-3">
                                <img id="previewPortada"
                                    src="{{ $canal->portada ? asset($canal->portada) : asset('img/cover-default.png') }}">
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
