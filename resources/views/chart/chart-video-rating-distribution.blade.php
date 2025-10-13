@extends('layouts.app')

@section('content')
    <div class="mx-auto chart-container">
        <div class="p-6 m-10 chartBorder rounded shadow">
            {!! $chart->container() !!}
        </div>
        <div class="chart-reference-box">
            <h3>Referencia de Puntuaciones (Emojis):</h3>
            
            <div class="chart-emoji-item">
                <img src="{{ asset('img/emojis/1.png') }}" alt="Puntuación 1">
                <span>1</span>
            </div>
            
            <div class="chart-emoji-item">
                <img src="{{ asset('img/emojis/2.png') }}" alt="Puntuación 2">
                <span>2</span>
            </div>
            
            <div class="chart-emoji-item">
                <img src="{{ asset('img/emojis/3.png') }}" alt="Puntuación 3">
                <span>3</span>
            </div>
            
            <div class="chart-emoji-item">
                <img src="{{ asset('img/emojis/4.png') }}" alt="Puntuación 4">
                <span>4</span>
            </div>
            
            <div class="chart-emoji-item">
                <img src="{{ asset('img/emojis/5.png') }}" alt="Puntuación 5">
                <span>5</span>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-4">Volver</a>
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection