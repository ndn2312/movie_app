<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_id',
        'content',
        'parent_id'
    ];

    /**
     * Lấy người dùng đã bình luận
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy phim được bình luận
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Lấy comment cha
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Lấy các comment phản hồi
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
