<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Blitzvideo\UserController;
use App\Http\Controllers\Blitzvideo\CanalController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

});

Route::get('/usuarios', [UserController::class, 'ListarTodosLosUsuarios'])->name('usuarios');
Route::get('/usuario/{id}', [UserController::class, 'MostrarUsuarioPorId'])->name('usuario');
Route::post('/usuarios', [UserController::class, 'ListarUsuariosPorNombre'])->name('usuarios-nombre');
Route::get('/usuarios/crear', [UserController::class, 'MostrarFormularioCrearUsuario'])->name('crear.usuario');
Route::post('/usuarios/crear', [UserController::class, 'CrearUsuario'])->name('usuarios.store');
Route::get('/usuarios/{id}', [UserController::class, 'MostrarFormularioActualizarUsuario'])->name('update.usuario');
Route::put('/usuarios/{id}', [UserController::class, 'ActualizarUsuario'])->name('update.usuario');
Route::delete('/usuarios/{id}', [UserController::class, 'EliminarUsuario'])->name('eliminar.usuario');

Route::get('/canales', [CanalController::class, 'ListarCanales'])->name('listar.canales');
Route::post('/canales', [CanalController::class, 'ListarCanalesPorNombre'])->name('canales-nombre');


Route::post('/canal', [CanalController::class, 'CrearCanal'])->name('canales.store');
Route::get('/canal', [CanalController::class, 'MostrarFormularioCrearCanal'])->name('crear-canal');
Route::get('/canal/{id}', [CanalController::class, 'ListarCanalesPorId'])->name('canal.detalle');
Route::put('/canal/editar/{id}', [CanalController::class, 'EditarCanal'])->name('update.canal');
Route::get('/canal/editar/{id}', [CanalController::class, 'MostrarFormularioEditarCanal'])->name('update.canal');
Route::delete('/canal/{id}', [CanalController::class, 'DarDeBajaCanal'])->name('eliminar.canal');



Route::get('/', function () {return view('inicio');})->name('inicio');

Route::get('/videos', function () {return view('videos');})->name('videos');
Route::get('/publicidades', function () {return view('publicidades');})->name('publicidades');
Route::get('/estadisticas', function () {return view('estadisticas');})->name('estadisticas');
Route::get('/anuncios', function () {return view('anuncios');})->name('anuncios');
Route::get('/perfil', function () {return view('perfil');})->name('perfil');
Route::get('/ajustes', function () {return view('ajustes');})->name('ajustes');