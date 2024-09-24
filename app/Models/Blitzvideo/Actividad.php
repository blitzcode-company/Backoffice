<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    protected $connection = 'blitzvideo';
    protected $table = 'actividad';

    protected $fillable = [
        'user_id',
        'nombre',
        'detalles',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
