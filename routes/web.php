<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Blitzvideo\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::prefix('blitzvideo')->group(function () {
        Route::get('/users', [UserController::class, 'listarTodosLosUsuarios']);
        Route::get('/users/{id}', [UserController::class, 'listarUsuarioPorId']);
        Route::get('/users/nombre/{nombre}', [UserController::class, 'listarUsuariosPorNombre']);
    });

    Route::get('/', function () {
        return view('inicio');
    })->name('inicio');
    
    Route::get('/usuarios', function () {
        return view('usuarios');
    })->name('usuarios');
    
    Route::get('/canales', function () {
        return view('canales');
    })->name('canales');
    
    Route::get('/videos', function () {
        return view('videos');
    })->name('videos');
    
    Route::get('/publicidades', function () {
        return view('publicidades');
    })->name('publicidades');
    
    Route::get('/estadisticas', function () {
        return view('estadisticas');
    })->name('estadisticas');
    
    Route::get('/anuncios', function () {
        return view('anuncios');
    })->name('anuncios');
    
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');
    
    
    Route::get('/ajustes', function () {
        return view('ajustes');
    })->name('ajustes');
});
