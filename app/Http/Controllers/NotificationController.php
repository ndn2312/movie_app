<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Lấy danh sách thông báo cho người dùng hiện tại
     */
    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        // Lấy thông báo cho user hoặc thông báo chung (user_id = null)
        $notifications = Notification::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)  // Giới hạn 10 thông báo mới nhất
            ->get();

        // Đếm tổng số thông báo chưa đọc
        $unreadCount = Notification::forUser($user->id)
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Đánh dấu thông báo đã đọc
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::findOrFail($id);

        // Chỉ cho phép đánh dấu thông báo của chính user hoặc thông báo chung
        if ($notification->user_id === $user->id || $notification->user_id === null) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Không có quyền'], 403);
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();

        // Đánh dấu tất cả thông báo của user và thông báo chung là đã đọc
        Notification::forUser($user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Xóa một thông báo cụ thể
     */
    public function deleteNotification($id)
    {
        $user = Auth::user();
        $notification = Notification::findOrFail($id);

        // Chỉ cho phép xóa thông báo của chính user hoặc thông báo chung
        if ($notification->user_id === $user->id || $notification->user_id === null) {
            $notification->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Không có quyền'], 403);
    }

    /**
     * Xóa tất cả thông báo của người dùng
     */
    public function deleteAllNotifications()
    {
        $user = Auth::user();

        // Xóa tất cả thông báo của user và thông báo chung đã đọc
        Notification::forUser($user->id)
            ->where('is_read', true)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Tạo thông báo mới khi admin thêm phim hoặc tập phim
     * Phương thức này sẽ được gọi từ MovieController hoặc EpisodeController
     */
    public static function createNotification($type, $data)
    {
        $notification = [
            'type' => $type,
            'user_id' => null, // null = thông báo cho tất cả người dùng
            'title' => '',
            'message' => '',
            'is_read' => false
        ];

        if ($type === 'new_movie') {
            $notification['movie_id'] = $data['movie']->id;
            $notification['title'] = 'Phim mới: ' . $data['movie']->title;
            $notification['message'] = 'Phim "' . $data['movie']->title . '" vừa được thêm vào hệ thống.';
            $notification['image'] = $data['movie']->image;
            $notification['link'] = '/phim/' . $data['movie']->slug;
        } elseif ($type === 'new_episode') {
            $notification['movie_id'] = $data['movie']->id;
            $notification['episode_id'] = $data['episode']->id;
            $notification['title'] = 'Tập mới: ' . $data['movie']->title;
            $episodeName = $data['episode']->episode;
            if (strpos(strtolower($episodeName), 'tập') === 0) {
                $notification['message'] = $episodeName . ' của phim "' . $data['movie']->title . '" vừa được cập nhật.';
            } else {
                $notification['message'] = 'Tập ' . $episodeName . ' của phim "' . $data['movie']->title . '" vừa được cập nhật.';
            }
            $notification['image'] = $data['movie']->image;
            $notification['link'] = '/xem-phim/' . $data['movie']->slug . '/' . $data['episode']->episode;
        }

        Notification::create($notification);
    }
}
