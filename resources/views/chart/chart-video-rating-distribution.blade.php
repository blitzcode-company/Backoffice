@extends('layouts.app')

@section('content')
    <div class="mx-auto chart-container">
        <div class="p-6 m-10 bg-white rounded shadow">
            {!! $chart->container() !!}
        </div>
        <div style="padding: 1rem; margin: 2.5rem auto 1.5rem auto; background-color: white; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); text-align: center; display: flex; align-items: center; justify-content: center; gap: 1rem;">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-right: 1rem; margin-bottom: 0;">Referencia de Puntuaciones (Emojis):</h3>
            
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="{{ asset('img/emojis/1.png') }}" alt="Puntuación 1" style="width: 24px; height: 24px; display: inline-block;">
                <span style="color: #4A5568; font-size: 0.875rem; font-weight: 500;">1</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="{{ asset('img/emojis/2.png') }}" alt="Puntuación 2" style="width: 24px; height: 24px; display: inline-block;">
                <span style="color: #4A5568; font-size: 0.875rem; font-weight: 500;">2</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="{{ asset('img/emojis/3.png') }}" alt="Puntuación 3" style="width: 24px; height: 24px; display: inline-block;">
                <span style="color: #4A5568; font-size: 0.875rem; font-weight: 500;">3</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="{{ asset('img/emojis/4.png') }}" alt="Puntuación 4" style="width: 24px; height: 24px; display: inline-block;">
                <span style="color: #4A5568; font-size: 0.875rem; font-weight: 500;">4</span>
            </div>
            
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <img src="{{ asset('img/emojis/5.png') }}" alt="Puntuación 5" style="width: 24px; height: 24px; display: inline-block;">
                <span style="color: #4A5568; font-size: 0.875rem; font-weight: 500;">5</span>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-primary mt-4">Volver</a>
    </div>

    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endsection