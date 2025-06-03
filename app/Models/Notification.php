<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'movie_id',
        'episode_id',
        'title',
        'message',
        'image',
        'link',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với Movie
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    // Quan hệ với Episode
    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

    // Scope để lấy thông báo chưa đọc
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope để lấy thông báo của 1 user cụ thể hoặc cho tất cả user
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhereNull('user_id');
        });
    }

    // Đánh dấu thông báo đã đọc
    public function markAsRead()
    {
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
    }
}
