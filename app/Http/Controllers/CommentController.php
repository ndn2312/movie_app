<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Thêm bình luận mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'movie_id' => $request->movie_id,
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->back()->with('success', 'Đã thêm bình luận thành công!');
    }

    /**
     * Cập nhật bình luận
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $comment = Comment::findOrFail($id);

        // Kiểm tra quyền sửa bình luận
        if ($comment->user_id !== Auth::id() && !Auth::user()->role) {
            return redirect()->back()->with('error', 'Bạn không có quyền sửa bình luận này!');
        }

        $comment->content = $request->content;
        $comment->save();

        return redirect()->back()->with('success', 'Đã cập nhật bình luận thành công!');
    }

    /**
     * Xóa bình luận
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // Kiểm tra quyền xóa bình luận
        if ($comment->user_id !== Auth::id() && !Auth::user()->role) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này!');
        }

        // Xóa cả phản hồi của bình luận
        Comment::where('parent_id', $comment->id)->delete();
        $comment->delete();

        return redirect()->back()->with('success', 'Đã xóa bình luận thành công!');
    }
}
