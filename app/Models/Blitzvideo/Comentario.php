<?php

namespace App\Models\Blitzvideo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comentario extends Model
{
    use SoftDeletes;

    protected $connection = 'blitzvideo';
    
    protected $fillable = [
        'usuario_id',
        'video_id',
        'respuesta_id',
        'mensaje',
        'bloqueado',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'bloqueado' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class, 'video_id');
    }

    public function respuesta()
    {
        return $this->belongsTo(Comentario::class, 'respuesta_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Comentario::class, 'respuesta_id');
    }

    public function likes()
    {
        return $this->hasMany(MeGusta::class);
    }

    public function likedByUser($userId)
    {
        return $this->likes()->where('usuario_id', $userId)->exists();
    }

    public function reportes()
    {
        return $this->hasMany(ReportaComentario::class);
    }
}
