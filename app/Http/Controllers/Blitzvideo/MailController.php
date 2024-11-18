<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Events\ActividadRegistrada;
use App\Http\Controllers\Controller;
use App\Jobs\EnviarCorreoJob;
use App\Models\Blitzvideo\User;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function enviarCorreoPorFormulario(Request $request)
    {
        $destinatario = $request->input('destinatario');
        $asunto = $request->input('asunto');
        $mensaje = $request->input('mensaje');
        $redireccion = $request->input('ruta');

        $this->enviarCorreo($destinatario, $asunto, $mensaje);
        $this->registrarActividadEnviarCorreo($destinatario, $asunto);

        return redirect()->to($redireccion)->with('success', 'Correo enviado exitosamente.');
    }

    private function registrarActividadEnviarCorreo($destinatario, $asunto)
    {
        $usuario = User::where('email', $destinatario)->first();
        $detalles = sprintf(
            'Enviado a: %s; ID destinatario: %d; Asunto: %s;',
            $destinatario,
            $usuario ? $usuario->id : 'No disponible',
            $asunto
        );
        event(new ActividadRegistrada('Correo enviado', $detalles));
    }

    private function enviarCorreo($destinatario, $asunto, $mensaje)
    {
        EnviarCorreoJob::dispatch($destinatario, $asunto, $mensaje)
            ->onQueue('cola_correo');
    }

    public function enviarCorreoConPlantilla($destinatario, $clavePlantilla, array $variables)
    {
        $template = EmailTemplate::where('clave_plantilla', $clavePlantilla)->first();

        if (!$template) {
            return response()->json(['error' => 'Plantilla de correo no encontrada.'], 404);
        }

        $cuerpo = $template->cuerpo;
        foreach ($variables as $clave => $valor) {
            $cuerpo = str_replace('{{ ' . $clave . ' }}', $valor, $cuerpo);
        }

        $this->enviarCorreo($destinatario, $template->asunto, $cuerpo);

        return response()->json(['success' => 'Correo enviado exitosamente.']);
    }

    public function correoBajaDeCanal($destinatario, $nombre_usuario, $nombre_canal, $motivo_baja)
    {
        return $this->enviarCorreoConPlantilla($destinatario, 'canal_baja', [
            'nombre_usuario' => $nombre_usuario,
            'nombre_canal' => $nombre_canal,
            'motivo_baja' => $motivo_baja,
        ]);
    }

    public function correoBloqueoDeVideo($destinatario, $nombre_usuario, $titulo_video, $motivo_bloqueo)
    {
        return $this->enviarCorreoConPlantilla($destinatario, 'video_bloqueo', [
            'nombre_usuario' => $nombre_usuario,
            'titulo_video' => $titulo_video,
            'motivo_bloqueo' => $motivo_bloqueo,
        ]);
    }

    public function correoBloqueoDeUsuario($destinatario, $nombre_usuario, $motivo_bloqueo)
    {
        return $this->enviarCorreoConPlantilla($destinatario, 'usuario_bloqueo', [
            'nombre_usuario' => $nombre_usuario,
            'motivo_bloqueo' => $motivo_bloqueo,
        ]);
    }
}
