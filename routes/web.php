<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Blitzvideo\CanalController;
use App\Http\Controllers\Blitzvideo\ComentarioController;
use App\Http\Controllers\Blitzvideo\EtiquetaController;
use App\Http\Controllers\Blitzvideo\MailController;
use App\Http\Controllers\Blitzvideo\PlaylistController;
use App\Http\Controllers\Blitzvideo\PublicidadController;
use App\Http\Controllers\Blitzvideo\StreamController;
use App\Http\Controllers\Blitzvideo\SuscriptoresController;
use App\Http\Controllers\Blitzvideo\TransaccionController;
use App\Http\Controllers\Blitzvideo\UserController;
use App\Http\Controllers\Blitzvideo\VideoController;
use App\Http\Controllers\Chart\UserChartController;
use App\Http\Controllers\Chart\VideoChartController;
use App\Http\Controllers\Chart\CanalChartController;
use App\Http\Controllers\Chart\OtherChartController;
use App\Http\Controllers\Chart\StatsSummaryController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::middleware(['auth'])->group(function () {

    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', function () {return view('inicio');})->name('inicio');
    Route::get('/estadisticas', [StatsSummaryController::class, 'showSummary'])->name('estadisticas');
    Route::get('/anuncios', function () {return view('anuncios');})->name('anuncios');
    Route::get('/perfil', function () {return view('perfil');})->name('perfil');
    Route::get('/ajustes', function () {return view('ajustes');})->name('ajustes');
    Route::post('/correo', [MailController::class, 'enviarCorreoPorFormulario'])->name('correo.enviar');

    Route::prefix('usuario')->name('usuario.')->group(function () {
        Route::get('/', [UserController::class, 'ListarTodosLosUsuarios'])->name('listar');
        Route::get('/buscar', [UserController::class, 'ListarUsuariosPorNombre'])->name('nombre');
        Route::get('/formulario', [UserController::class, 'MostrarFormularioCrearUsuario'])->name('crear.formulario');
        Route::post('/', [UserController::class, 'CrearUsuario'])->name('crear');
        Route::get('/{id}', [UserController::class, 'MostrarUsuarioPorId'])->name('detalle');
        Route::get('/{id}/formulario', [UserController::class, 'MostrarFormularioActualizarUsuario'])->name('editar.formulario');
        Route::put('/{id}', [UserController::class, 'ActualizarUsuario'])->name('editar');
        Route::delete('/{id}', [UserController::class, 'EliminarUsuario'])->name('eliminar');
        Route::post('/{id}/bloquear', [UserController::class, 'bloquearUsuario'])->name('bloquear');
        Route::post('/{id}/desbloquear', [UserController::class, 'desbloquearUsuario'])->name('desbloquear');
    });

    Route::prefix('transaccion')->name('transaccion.')->group(function () {
        Route::get('/', [TransaccionController::class, 'filtrar'])->name('filtrar');
        Route::get('/exportar', [TransaccionController::class, 'exportar'])->name('exportar');
    });

    Route::prefix('canal')->name('canal.')->group(function () {
        Route::get('/', [CanalController::class, 'ListarCanales'])->name('listar');
        Route::get('/buscar', [CanalController::class, 'ListarCanalesPorNombre'])->name('nombre');
        Route::get('/crear', [CanalController::class, 'MostrarFormularioCrearCanal'])->name('crear.formulario');
        Route::post('/', [CanalController::class, 'CrearCanal'])->name('crear');
        Route::get('/{id}', [CanalController::class, 'ListarCanalesPorId'])->name('detalle');
        Route::get('/{id}/formulario', [CanalController::class, 'MostrarFormularioEditarCanal'])->name('editar.formulario');
        Route::put('/editar/{id}', [CanalController::class, 'EditarCanal'])->name('editar');
        Route::delete('/{id}', [CanalController::class, 'DarDeBajaCanal'])->name('eliminar');
    });

    Route::prefix('/suscriptores')->name('suscriptores.')->group(function () {
        Route::get('/canal/{id}', [SuscriptoresController::class, 'listarSuscriptores'])->name('listar');
        Route::get('/canal/{id}/buscar', [SuscriptoresController::class, 'listarSuscriptoresPorNombre'])->name('nombre');
        Route::delete('/canal/{canalId}/desuscribir/{suscribeId}', [SuscriptoresController::class, 'desuscribir'])->name('desuscribir');
        Route::post('/canal/{canalId}/suscribir', [SuscriptoresController::class, 'suscribir'])->name('suscribir');
    });

    Route::prefix('video')->name('video.')->group(function () {
        Route::get('/', [VideoController::class, 'MostrarTodosLosVideos'])->name('listar');
        Route::post('/', [VideoController::class, 'ListarVideosPorNombre'])->name('nombre');
        Route::get('/subir', [VideoController::class, 'MostrarFormularioSubida'])->name('crear.formulario');
        Route::post('/subir', [VideoController::class, 'SubirVideo'])->name('crear');
        Route::get('/etiquetas', [VideoController::class, 'MostrarEtiquetasConConteoVideos'])->name('etiquetas');
        Route::get('/etiquetas/{id}', [VideoController::class, 'listarVideosPorEtiqueta'])->name('etiqueta');
        Route::get('/{id}/detalle', [VideoController::class, 'MostrarInformacionVideo'])->name('detalle');
        Route::get('/{id}', [VideoController::class, 'MostrarFormularioEditar'])->name('editar.formulario');
        Route::put('/{id}', [VideoController::class, 'EditarVideo'])->name('editar');

        Route::patch('/{id}/bloquear', [VideoController::class, 'bloquearVideo'])->name('bloquear');
        Route::patch('/{id}/desbloquear', [VideoController::class, 'desbloquearVideo'])->name('desbloquear');

        Route::delete('/{id}', [VideoController::class, 'BajaVideo'])->name('eliminar');
        Route::get('/canal/{id}', [VideoController::class, 'ListarVideosPorCanal'])->name('canal');
    });
    Route::prefix('publicidad')->name('publicidad.')->group(function () {
        Route::get('/', [PublicidadController::class, 'listarPublicidades'])->name('listar');
        Route::get('/formulario', [PublicidadController::class, 'formulario'])->name('crear.formulario');
        Route::get('/{id}/formulario', [PublicidadController::class, 'formularioEditar'])->name('editar.formulario');
        Route::post('/', [PublicidadController::class, 'crearPublicidad'])->name('crear');
        Route::put('/{id}', [PublicidadController::class, 'modificarPublicidad'])->name('editar');
        Route::delete('/{id}', [PublicidadController::class, 'eliminarPublicidad'])->name('eliminar');
    });
    Route::prefix('playlists')->name('playlists.')->group(function () {
        Route::get('/buscar/video', [PlaylistController::class, 'buscar'])->name('buscar');
        Route::get('/formulario', [PlaylistController::class, 'formulario'])->name('crear.formulario');
        Route::post('/', [PlaylistController::class, 'crearPlaylist'])->name('crear');
        Route::get('/', [PlaylistController::class, 'listarPlaylists'])->name('listar');
        Route::get('/usuario/{id}', [PlaylistController::class, 'listarPlaylists'])->name('usuario.listar');
        Route::get('/{id}/videos', [PlaylistController::class, 'mostrarVideosDePlaylist'])->name('videos');
        Route::post('/{id}/acceso', [PlaylistController::class, 'cambiarAcceso'])->name('acceso');
        Route::delete('/{id}', [PlaylistController::class, 'borrarPlaylist'])->name('eliminar');
        Route::put('/{id}', [PlaylistController::class, 'editarNombrePlaylist'])->name('editar');

    });

    Route::prefix('etiquetas')->name('etiquetas.')->group(function () {
        Route::get('/', [EtiquetaController::class, 'MostrarEtiquetas'])->name('listar');
        Route::post('/', [EtiquetaController::class, 'CrearEtiqueta'])->name('crear');
        Route::put('/{id}', [EtiquetaController::class, 'ActualizarEtiqueta'])->name('editar');
        Route::delete('/{id}', [EtiquetaController::class, 'EliminarEtiquetaYAsignaciones'])->name('eliminar');
    });

    Route::prefix('comentarios')->name('comentarios.')->group(function () {
        Route::get('/video/{id}', [ComentarioController::class, 'ListarComentarios'])->name('listado');
        Route::post('/', [ComentarioController::class, 'CrearComentario'])->name('crear');
        Route::post('/respuesta', [ComentarioController::class, 'responderComentario'])->name('responder');
        Route::get('/respuestas/{comentario_id}', [ComentarioController::class, 'VerComentario'])->name('ver');
        Route::delete('/{comentario_id}', [ComentarioController::class, 'eliminarComentario'])->name('eliminar');
        Route::post('/{comentario_id}/restaurar', [ComentarioController::class, 'restaurarComentario'])->name('restaurar');
        Route::put('/{comentario_id}', [ComentarioController::class, 'actualizarComentario'])->name('actualizar');
        Route::patch('/{comentario_id}/bloquear', [ComentarioController::class, 'bloquearComentario'])->name('bloquear');
        Route::patch('/{comentario_id}/desbloquear', [ComentarioController::class, 'desbloquearComentario'])->name('desbloquear');
    });

    Route::prefix('charts')->name('charts.')->group(function () {
        Route::get('/usuarios-premium', [UserChartController::class, 'UsuariosPremium'])->name('premium_users');
        Route::get('/usuarios-activos', [UserChartController::class, 'UsuarioActivoInactivo'])->name('active_users');
        Route::get('/usuarios-creadores', [UserChartController::class, 'UsuarioConCanal'])->name('user_channel');
        Route::get('/usuarios-comentarios-me-gusta', [UserChartController::class, 'UsuariosInteraccionGeneral'])->name('commenting_liking_users');
        Route::get('/usuarios-registrados-por-mes', [UserChartController::class, 'UsuariosRegistradosPorMes'])->name('monthly_registrations');
        Route::get('/usuarios-bloqueados-activos', [UserChartController::class, 'UsuariosBloqueadosActivos'])->name('blocked_active_users');
        Route::get('/videos-por-etiqueta', [VideoChartController::class, 'VideosPorEtiqueta'])->name('videos_por_etiqueta');
        Route::get('/videos-mas-vistados-por-mes', [VideoChartController::class, 'VideosMasVistadosElUltimoMes'])->name('mas_visitados_por_mes');
        Route::get('/videos-activos-bloqueados', [VideoChartController::class, 'VideosActivosBloqueados'])->name('video_status');
        Route::get('/videos-nivel-acceso', [VideoChartController::class, 'VideosPorNivelDeAcceso'])->name('video_access_level');
        Route::get('/videos-mas-comentados', [VideoChartController::class, 'VideosConMasComentarios'])->name('top_commented_videos');
        Route::get('/videos-distribucion-puntuaciones', [VideoChartController::class, 'DistribucionPuntuacionesVideos'])->name('video_rating_distribution');
        Route::get('/videos-publicidad', [VideoChartController::class, 'PublicidadVideos'])->name('video_ads');
        Route::get('/canales-mas-seguidos', [CanalChartController::class, 'TopCanalesSeguidos'])->name('top_followed_channels');
        Route::get('/canales-por-videos', [CanalChartController::class, 'CanalesPorCantidadVideos'])->name('channels_by_video_count');
        Route::get('/canales-streams-estado', [CanalChartController::class, 'CanalesStreamsActivosInactivos'])->name('channel_stream_status');
        Route::get('/canales-antiguedad', [CanalChartController::class, 'CanalesPorAntiguedad'])->name('channel_creation_date');

        Route::get('/reportes-contenido', [OtherChartController::class, 'ReportesContenidoPorCategoria'])->name('content_reports');
        Route::get('/uso-notificaciones', [OtherChartController::class, 'UsoNotificaciones'])->name('notification_status');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'listarUsuarios'])->name('usuarios');
        Route::get('/usuario/{id}/actividades', [AdminController::class, 'listarActividadesPorUsuario'])->name('actividades');
    });

    Route::prefix('stream')->name('stream.')->group(function () {
        Route::get('/', [StreamController::class, 'ListarStreams'])->name('streams');
        Route::get('/{id}/live', [StreamController::class, 'MostrarStream'])->name('detalle');
        Route::post('/', [StreamController::class, 'ListarStreamsPorNombre'])->name('nombre');
        Route::get('/crear', [StreamController::class, 'MostrarFormularioSubidaStream'])->name('crear.formulario');
        Route::get('/{id}/editar', [StreamController::class, 'MostrarFormularioEditarStream'])->name('editar.formulario');
        Route::put('/{id}', [StreamController::class, 'EditarStream'])->name('editar');
        Route::post('/crear', [StreamController::class, 'CrearStream'])->name('crear');
        Route::delete('/{id}', [StreamController::class, 'EliminarStream'])->name('eliminar');
    });

});
