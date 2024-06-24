<?php

namespace App\Models\Blitzvideo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Canal extends Model
{
    use SoftDeletes;
    
    protected $connection = 'blitzvideo';
    
    protected $table = 'canals';
    protected $fillable = [
        'nombre', 'descripcion', 'portada', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
