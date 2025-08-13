@extends('layouts.app')

@section('content')
    <div class="mx-auto chart-container">
        <div class="flex flex-col md:flex-row md:space-x-4">
            <div class="p-6 m-10 bg-white rounded shadow w-full md:w-1/2 mb-4 md:mb-0">
                {!! $videoAdChart->container() !!}
            </div>
            <div class="p-6 m-10 bg-white rounded shadow w-full md:w-1/2">
                {!! $topAdViewsChart->container() !!}
            </div>
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-primary mt-4">Volver</a>
    </div>

    <script src="{{ $videoAdChart->cdn() }}"></script>
    {{ $videoAdChart->script() }}
    {{ $topAdViewsChart->script() }}
@endsection 