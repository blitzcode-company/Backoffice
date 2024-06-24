<?php

namespace App\Models\Blitzvideo;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $connection = 'blitzvideo';
   
    protected $fillable = [
        'user_id',
        'video_id',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
