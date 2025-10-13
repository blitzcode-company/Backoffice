@extends('layouts.app')

@section('content')
    <div class="mx-auto chart-container">
        <div class="flex flex-col md:flex-row md:space-x-4">
            <div class="p-6 m-10 rounded chartBorder shadow w-full md:w-1/2 mb-4 md:mb-0">
                {!! $videoReportsChart->container() !!}
            </div>
            <div class="p-6 m-10 rounded chartBorder shadow w-full md:w-1/2">
                {!! $commentReportsChart->container() !!}
            </div>
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-primary mt-4">Volver</a>
    </div>
    <script src="{{ $videoReportsChart->cdn() }}"></script>
    {{ $videoReportsChart->script() }}
    {{ $commentReportsChart->script() }}
@endsection
