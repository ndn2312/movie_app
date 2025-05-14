<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{

    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'linkphim',
        'episode',
        'server_name',
        'created_at',
        'updated_at'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}