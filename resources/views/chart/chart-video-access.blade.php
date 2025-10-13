@extends('layouts.app')

@section('content')
    <div class="mx-auto chart-container">
        <div class="p-6 m-10 chartBorder rounded shadow">
            {!! $chart->container() !!}
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-4">Volver</a>
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection