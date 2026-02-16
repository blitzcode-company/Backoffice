<?php

namespace App\Models\Blitzvideo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $connection = 'blitzvideo';

    protected $fillable = [
        'video_id',
        'stream_programado',
        'max_viewers',
        'total_viewers',
        'activo',
    ];

    public function canal()
    {
        return $this->belongsTo(Canal::class);
    }
}
