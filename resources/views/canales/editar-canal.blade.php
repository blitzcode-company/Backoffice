@extends('layouts.app')

@section('content')
    <div class="m-auto" style="max-width: 900px;">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="titulo mb-0 border-0 p-0" style="font-size: 1.5rem; font-weight: 700;">Editar Canal</h2>
            </div>
            <span class="badge bg-secondary">ID: {{ $canal->id }}</span>
        </div>

        <div class="card border">
            <div class="card-body p-0">
                <form action="{{ route('canal.editar', $canal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="position-relative w-100 bg-light border-bottom" style="height: 220px; overflow: hidden;">
                        <img id="previewPortada" src="{{ $canal->portada ? asset($canal->portada) : asset('img/cover-default.png') }}" class="w-100 h-100" style="object-fit: cover;">
                        
                        <label for="portada" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                               style="background: rgba(0,0,0,0.4); opacity: 0; transition: opacity 0.2s; cursor: pointer;" 
                               onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                            <div class="btn btn-light btn-sm fw-bold">
                                <i class="fas fa-camera me-2"></i> Cambiar Portada
                            </div>
                        </label>
                        <input type="file" name="portada" id="portada" class="d-none" onchange="previewImage(this)">
                    </div>
                    <div class="px-4 py-2 banner-info border-bottom text-end">
                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Haz clic en la imagen para actualizar el banner</small>
                    </div>

                    <div class="p-4">
                        <div class="mb-4">
                            <label for="nombre" class="form-label fw-bold text-secondary small text-uppercase">Nombre del Canal</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required value="{{ $canal->nombre }}">
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-bold text-secondary small text-uppercase">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" rows="6" required>{{ $canal->descripcion }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end pt-3 border-top">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary me-2 px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="fas fa-save me-2"></i> Actualizar
                            </button>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger m-4 mt-0">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
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
