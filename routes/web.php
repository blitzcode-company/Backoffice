<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Blitzvideo\UserController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

});

Route::get('/usuarios', [UserController::class, 'listarTodosLosUsuarios'])->name('usuarios');
Route::get('/usuario/{id}', [UserController::class, 'mostrarUsuarioPorId'])->name('usuario');
Route::post('/usuarios', [UserController::class, 'listarUsuariosPorNombre'])->name('usuarios-nombre');
Route::get('/usuarios/crear', [UserController::class, 'mostrarFormularioCrearUsuario'])->name('crear.usuario');
Route::post('/usuarios/crear', [UserController::class, 'crearUsuario'])->name('usuarios.store');

Route::get('/usuarios/{id}', [UserController::class, 'mostrarFormularioActualizarUsuario'])->name('update.usuario');
Route::put('/usuarios/{id}', [UserController::class, 'actualizarUsuario'])->name('update.usuario');


Route::delete('/usuarios/{id}/eliminar', [UserController::class, 'eliminarUsuario'])->name('eliminar.usuario');

Route::get('/', function () {return view('inicio');})->name('inicio');
Route::get('/canales', function () {return view('canales');})->name('canales');
Route::get('/videos', function () {return view('videos');})->name('videos');
Route::get('/publicidades', function () {return view('publicidades');})->name('publicidades');
Route::get('/estadisticas', function () {return view('estadisticas');})->name('estadisticas');
Route::get('/anuncios', function () {return view('anuncios');})->name('anuncios');
Route::get('/perfil', function () {return view('perfil');})->name('perfil');
Route::get('/ajustes', function () {return view('ajustes');})->name('ajustes');