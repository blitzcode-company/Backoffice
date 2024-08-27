<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Blitzvideo\CanalController;
use App\Http\Controllers\Blitzvideo\EtiquetaController;
use App\Http\Controllers\Blitzvideo\UserController;
use App\Http\Controllers\Blitzvideo\VideoController;
use App\Http\Controllers\Chart\UserChartController;
use App\Http\Controllers\Chart\VideoChartController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

//Route::middleware(['auth'])->group(function () {
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

    Route::get('/videos', [VideoController::class, 'MostrarTodosLosVideos'])->name('listar.videos');
    Route::post('/videos', [VideoController::class, 'ListarVideosPorNombre'])->name('videos-nombre');
    Route::get('/subir-video', [VideoController::class, 'MostrarFormularioSubida'])->name('videos.subir');
    Route::post('/subir-video', [VideoController::class, 'SubirVideo'])->name('videos.subir');
    Route::delete('/videos/{id}/baja', [VideoController::class, 'BajaVideo'])->name('eliminar.video');
    Route::get('/canal/{id}/video', [VideoController::class, 'MostrarInformacionVideo'])->name('video');

    Route::get('/editar-video/{id}', [VideoController::class, 'MostrarFormularioEditar'])->name('videos.editar');
    Route::put('/video/{id}', [VideoController::class, 'EditarVideo'])->name('videos.actualizar');

    Route::get('/etiquetas', [EtiquetaController::class, 'MostrarEtiquetas'])->name('etiquetas');
    Route::post('/etiquetas', [EtiquetaController::class, 'CrearEtiqueta'])->name('etiquetas.crear');
    Route::put('/etiquetas/{id}', [EtiquetaController::class, 'ActualizarEtiqueta'])->name('etiquetas.actualizar');
    Route::delete('/etiquetas/{id}', [EtiquetaController::class, 'EliminarEtiquetaYAsignaciones'])->name('etiquetas.eliminar');

    Route::get('/charts/usuarios-premium', [UserChartController::class, 'UsuariosPremium'])->name('charts.premium_users');
    Route::get('/charts/usuarios-activos', [UserChartController::class, 'UsuarioActivoInactivo'])->name('charts.active_users');
    Route::get('charts/usuarios-creadores', [UserChartController::class, 'UsuarioConCanal'])->name('charts.user_channel');
    Route::get('/charts/videos-por-etiqueta', [VideoChartController::class, 'VideosPorEtiqueta'])->name('charts.videos_por_etiqueta');
    Route::get('charts/videos-mas-vistados-por-mes', [VideoChartController::class, 'VideosMasVistadosElUltimoMes'])->name('charts.mas_visitados_por_mes');

    Route::get('/', function () {return view('inicio');})->name('inicio');
    Route::get('/estadisticas', function () {return view('estadisticas');})->name('estadisticas');
    Route::get('/anuncios', function () {return view('anuncios');})->name('anuncios');
    Route::get('/perfil', function () {return view('perfil');})->name('perfil');
    Route::get('/ajustes', function () {return view('ajustes');})->name('ajustes');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
//});
