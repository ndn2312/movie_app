<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    public $timestamps = true; // Thay đổi từ false thành true để lưu thời gian đánh giá
    protected $fillable = [
        'id',
        'movie_id',
        'rating',
        'ip_address',
    ];
    protected $table = 'rating';
}