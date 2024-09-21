<?php

namespace App\Http\Controllers\Blitzvideo;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function enviarCorreoPorFormulario(Request $request)
    {
        $destinatario = $request->input('destinatario');
        $asunto = $request->input('asunto');
        $mensaje = $request->input('mensaje');
        $redireccion = $request->input('ruta');

        Mail::send([], [], function ($correo) use ($destinatario, $asunto, $mensaje) {
            $correo->to($destinatario)
                ->subject($asunto)
                ->setBody(view('emails.plantilla', ['asunto' => $asunto, 'mensaje' => $mensaje])->render(), 'text/html');
        });
        return redirect()->to($redireccion)->with('success', 'Correo enviado exitosamente.');
    }

    public function enviarCorreo($destinatario, $asunto, $mensaje)
    {
        Mail::send([], [], function ($correo) use ($destinatario, $asunto, $mensaje) {
            $correo->to($destinatario)
                ->subject($asunto)
                ->setBody(view('emails.plantilla', ['asunto' => $asunto, 'mensaje' => $mensaje])->render(), 'text/html');
        });
    }

    public function correoBajaDeCanal($destinatario, $nombre_usuario, $nombre_canal, $motivo_baja)
    {
        $template = EmailTemplate::where('clave_plantilla', 'canal_baja')->first();
        if (!$template) {
            return response()->json(['error' => 'Plantilla de correo no encontrada.'], 404);
        }
        $cuerpo = str_replace('{{ nombre_usuario }}', $nombre_usuario, $template->cuerpo);
        $cuerpo = str_replace('{{ nombre_canal }}', $nombre_canal, $cuerpo);
        $cuerpo = str_replace('{{ motivo_baja }}', $motivo_baja, $cuerpo);
        $this->enviarCorreo($destinatario, $template->asunto, $cuerpo);
        return response()->json(['success' => 'Correo enviado exitosamente.']);
    }
}
