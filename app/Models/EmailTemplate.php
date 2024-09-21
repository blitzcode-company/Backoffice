<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    protected $fillable = [
        'clave_plantilla',
        'asunto',
        'cuerpo',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
