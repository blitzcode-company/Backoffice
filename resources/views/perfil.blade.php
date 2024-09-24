@extends('layouts.app')

@section('content')
    <h1>Perfil de Usuario</h1>
    
    @if (Auth::check())
        @php
            $user = Auth::user();
            $session = \DB::table('sessions')->where('user_id', $user->id)->first();
            $lastActivity = $session ? $session->last_activity : null;
        @endphp

        <div class="user-profile">
            <h2>Detalles del Usuario</h2>
            <ul>
                <li><strong>ID:</strong> {{ $user->id }}</li>
                <li><strong>Nombre:</strong> {{ $user->name }}</li>
                <li><strong>Usuario:</strong> {{ $user->username }}</li>
                <li><strong>Dominio:</strong> {{ $user->domain }}</li>
                <li><strong>Correo Electrónico:</strong> {{ $user->email }}</li>
                <li><strong>Fecha de Creación:</strong> {{ $user->created_at->format('d/m/Y') }}</li>
                <li><strong>Última Actualización:</strong> {{ $user->updated_at->format('d/m/Y') }}</li>
                <li><strong>Última Actividad:</strong> {{ $lastActivity ? date('d/m/Y H:i:s', $lastActivity) : 'No disponible' }}</li>
            </ul>
        </div>
    @else
        <p>No has iniciado sesión.</p>
    @endif
@endsection
