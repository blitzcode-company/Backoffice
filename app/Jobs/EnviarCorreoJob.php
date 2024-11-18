<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class EnviarCorreoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $destinatario;
    public $asunto;
    public $mensaje;

    public function __construct($destinatario, $asunto, $mensaje)
    {
        $this->destinatario = $destinatario;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
    }

    public function handle()
    {
        Mail::send([], [], function ($correo) {
            $correo->to($this->destinatario)
                ->subject($this->asunto)
                ->setBody(
                    view('emails.plantilla', [
                        'asunto' => $this->asunto,
                        'mensaje' => $this->mensaje,
                    ])->render(),
                    'text/html'
                );
        });
    }
}
