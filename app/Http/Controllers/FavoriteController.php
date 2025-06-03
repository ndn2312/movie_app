<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class FavoriteController extends Controller
{
    /**
     * Hiển thị danh sách phim yêu thích của người dùng
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $sort = $request->get('sort', 'latest');

        // Lấy danh sách thể loại, quốc gia và danh mục cho menu
        $genre = \App\Models\Genre::orderBy('id', 'DESC')->get();
        $country = \App\Models\Country::orderBy('id', 'DESC')->get();
        $category = \App\Models\Category::orderBy('id', 'DESC')->get();

        // Lấy danh sách phim yêu thích
        $favoritesQuery = Favorite::where('user_id', $user->id)
            ->with('movie.category', 'movie.country', 'movie.genre');

        // Sắp xếp theo tiêu chí
        switch ($sort) {
            case 'oldest':
                $favoritesQuery->orderBy('created_at', 'asc');
                break;
            case 'name':
                // Cần phải lấy danh sách trước, sau đó sắp xếp theo tên
                $movies = $favoritesQuery->get()
                    ->pluck('movie')
                    ->sortBy('title');
                break;
            case 'latest':
            default:
                $favoritesQuery->orderBy('created_at', 'desc');
                break;
        }

        // Nếu không phải sắp xếp theo tên thì lấy kết quả từ query builder
        if ($sort !== 'name') {
            $movies = $favoritesQuery->get()->pluck('movie');
        }

        // Chuyển sang pagination collection
        $favorites = new \Illuminate\Pagination\LengthAwarePaginator(
            $movies->slice(($request->get('page', 1) - 1) * 12, 12),
            $movies->count(),
            12,
            $request->get('page', 1)
        );

        // Giữ lại tham số sort khi phân trang
        $favorites->appends(['sort' => $sort]);
        $favorites->withPath(route('favorites'));

        return view('pages.favorites', compact('favorites', 'genre', 'country', 'category'));
    }

    /**
     * Thêm phim vào danh sách yêu thích
     */
    public function store(Request $request)
    {
        $movie_id = $request->movie_id;
        $user_id = Auth::id();

        // Kiểm tra xem phim đã được yêu thích chưa
        $favorite = Favorite::where('user_id', $user_id)
            ->where('movie_id', $movie_id)
            ->first();

        if ($favorite) {
            // Nếu đã có thì xóa đi (toggle favorite)
            $favorite->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Đã xóa khỏi danh sách yêu thích'
            ]);
        } else {
            // Nếu chưa có thì thêm vào
            Favorite::create([
                'user_id' => $user_id,
                'movie_id' => $movie_id
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Đã thêm vào danh sách yêu thích'
            ]);
        }
    }

    /**
     * Kiểm tra xem phim có trong danh sách yêu thích không
     */
    public function check(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['is_favorite' => false]);
        }

        $movie_id = $request->movie_id;
        $user_id = Auth::id();

        $favorite = Favorite::where('user_id', $user_id)
            ->where('movie_id', $movie_id)
            ->first();

        return response()->json(['is_favorite' => !empty($favorite)]);
    }

    /**
     * Xóa phim khỏi danh sách yêu thích
     */
    public function destroy($id)
    {
        $user_id = Auth::id();

        $favorite = Favorite::where('user_id', $user_id)
            ->where('movie_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Đã xóa phim khỏi danh sách yêu thích');
        }

        return redirect()->back()->with('error', 'Không tìm thấy phim trong danh sách yêu thích');
    }
}
