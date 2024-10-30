@extends('layouts.app')

@section('content')
    <div class="titulo">
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
        <span>Crear Publicidad</span>
    </div>
    <div class="container-publicidad  container-card">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Nueva Publicidad - <span class="text-muted">Define los detalles de la publicidad.</span>
                    </div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('publicidad.crear') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="empresa" title="Nombre de la empresa responsalbe del anuncio.">Empresa</label>
                                <input type="text" name="empresa" id="empresa" class="form-control"
                                    value="{{ old('empresa') }}" placeholder="Nombre de la Empresa"
                                    title="Nombre de la empresa responsalbe del anuncio." required>
                            </div>

                            <div class="form-group">
                                <label for="prioridad"
                                    title="La prioridad es la regularidad con la que sale el video.">Prioridad</label>
                                <select name="prioridad" id="prioridad" class="form-control"
                                    title="La prioridad es la regularidad con la que sale el video." required>
                                    <option value="1">Alta</option>
                                    <option value="2">Media</option>
                                    <option value="3">Baja</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="video_id" title="Ingresa la ID del video para asociar el anuncio.">ID del
                                    Video</label>
                                <input type="number" title="Ingresa la ID del video para asociar el anuncio."
                                    name="video_id" id="video_id" class="form-control" value="{{ old('video_id') }}"
                                    placeholder="Ingrese ID del Video" required>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Crear Publicidad</button>
                        </form>

                        @if (session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
