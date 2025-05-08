<?php

namespace App\Models\Blitzvideo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportaComentario extends Model
{
    use SoftDeletes;

    protected $connection = 'blitzvideo';

    protected $table = 'reporta_comentario';

    const ESTADO_RESUELTO = 'resuelto';
    const ESTADO_PENDIENTE = 'pendiente';

    protected $fillable = [
        'user_id',
        'comentario_id',
        'detalle',
        'lenguaje_ofensivo',
        'spam',
        'contenido_enganoso',
        'incitacion_al_odio',
        'acoso',
        'contenido_sexual',
        'otros',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comentario()
    {
        return $this->belongsTo(Comentario::class);
    }
}
