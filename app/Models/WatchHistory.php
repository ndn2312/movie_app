<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{
    use HasFactory;

    protected $table = 'user_watching_history';

    protected $fillable = [
        'user_id',
        'movie_id',
        'episode',
        'current_time',
        'duration',
        'progress',
        'thumbnail',
        'last_watched_at'
    ];

    protected $casts = [
        'current_time' => 'float',
        'duration' => 'float',
        'progress' => 'float',
        'last_watched_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
