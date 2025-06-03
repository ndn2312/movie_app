<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WatchHistory;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WatchHistoryController extends Controller
{
    /**
     * Hiển thị lịch sử xem của người dùng
     */
    public function index()
    {
        $user = Auth::user();
        $histories = WatchHistory::where('user_id', $user->id)
            ->orderBy('last_watched_at', 'desc')
            ->with('movie')
            ->paginate(12);

        // Lấy dữ liệu cho layout chung
        $genre = \App\Models\Genre::orderBy('id', 'DESC')->get();
        $country = \App\Models\Country::orderBy('id', 'DESC')->get();
        $category = \App\Models\Category::orderBy('id', 'DESC')->get();

        return view('pages.history', compact('histories', 'genre', 'country', 'category'));
    }

    /**
     * Lưu hoặc cập nhật lịch sử xem
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|integer|exists:movies,id',
            'episode' => 'nullable|string',
            'current_time' => 'required|numeric|min:0',
            'duration' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $movie = Movie::find($request->movie_id);

        if (!$movie) {
            return response()->json(['success' => false, 'message' => 'Phim không tồn tại'], 404);
        }

        // Tính toán % tiến độ xem
        $progress = 0;
        if ($request->duration > 0) {
            $progress = min(100, round(($request->current_time / $request->duration) * 100, 2));
        }

        // Lấy thumbnail từ phim
        $thumbnail = $movie->image;

        $history = WatchHistory::updateOrCreate(
            [
                'user_id' => $user->id,
                'movie_id' => $request->movie_id,
                'episode' => $request->episode ?? null,
            ],
            [
                'current_time' => $request->current_time,
                'duration' => $request->duration,
                'progress' => $progress,
                'thumbnail' => $thumbnail,
                'last_watched_at' => Carbon::now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu tiến độ xem',
            'history' => $history
        ]);
    }

    /**
     * Lấy lịch sử xem cho phim và tập cụ thể
     */
    public function getHistory($movie_id, $episode = null)
    {
        $user = Auth::user();

        $history = WatchHistory::where('user_id', $user->id)
            ->where('movie_id', $movie_id)
            ->where('episode', $episode)
            ->first();

        if (!$history) {
            return response()->json(['success' => false, 'message' => 'Không có lịch sử xem'], 404);
        }

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    /**
     * Xóa một lịch sử xem
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $history = WatchHistory::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$history) {
            return response()->json(['success' => false], 404);
        }

        $history->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Xóa tất cả lịch sử xem của người dùng
     */
    public function clearAll()
    {
        $user = Auth::user();
        WatchHistory::where('user_id', $user->id)->delete();

        return response()->json(['success' => true]);
    }
}
