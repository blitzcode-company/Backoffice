<?php

namespace App\Models\Blitzvideo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suscribe extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $connection = 'blitzvideo';

    protected $table = 'suscribe';

    protected $fillable = [
        'user_id',
        'canal_id',
        'notificaciones',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function canal()
    {
        return $this->belongsTo(Canal::class);
    }
}
