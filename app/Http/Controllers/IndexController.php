<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Movie;
use App\Models\Episode;
use App\Models\Movie_Genre;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;




class IndexController extends Controller
{
    // public function locphim()
    // {

    public function advancedFilter(Request $request)
    {
        // BƯỚC 1: XỬ LÝ TRUY VẤN DỮ LIỆU PHIM
        $query = Movie::withCount('episode')->where('status', 1);

        // Lọc theo thể loại (genre)
        if ($request->has('genre') && is_array($request->genre)) {
            if (!in_array('all', $request->genre)) {
                // Lấy danh sách ID phim thuộc các thể loại được chọn
                $movie_ids = Movie_Genre::whereIn('genre_id', $request->genre)
                    ->pluck('movie_id')
                    ->unique() // Đảm bảo không lấy trùng phim
                    ->toArray();

                if (!empty($movie_ids)) {
                    $query->whereIn('id', $movie_ids);
                }
            }
        }

        // Lọc theo danh mục (category)
        if ($request->has('category') && is_array($request->category)) {
            if (!in_array('all', $request->category)) {
                $query->whereIn('category_id', $request->category);
            }
        }

        // Lọc theo quốc gia (country)
        if ($request->has('country') && is_array($request->country)) {
            if (!in_array('all', $request->country)) {
                $query->whereIn('country_id', $request->country);
            }
        }

        // Lọc theo năm phát hành (year)
        if ($request->has('year') && is_array($request->year)) {
            if (!in_array('all', $request->year)) {
                $query->whereIn('year', $request->year);
            }
        }

        // Lọc theo đánh giá (rating)
        if ($request->has('rating') && is_array($request->rating)) {
            if (!in_array('all', $request->rating)) {
                // Lấy danh sách phim có rating trung bình theo các mức đánh giá được chọn
                $movies_by_rating = DB::table('rating')
                    ->select('movie_id', DB::raw('ROUND(AVG(rating)) as avg_rating'))
                    ->groupBy('movie_id')
                    ->havingRaw('ROUND(AVG(rating)) IN (' . implode(',', $request->rating) . ')')
                    ->pluck('movie_id')
                    ->toArray();

                if (!empty($movies_by_rating)) {
                    $query->whereIn('id', $movies_by_rating);
                } else {
                    // Không tìm thấy phim nào phù hợp với rating
                    $query->where('id', 0); // Trả về không có kết quả
                }
            }
        }



        // Tìm kiếm theo từ khóa
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = trim($request->keyword);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('name_eng', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('description', 'LIKE', '%' . $keyword . '%');
            });
        }

        // Sắp xếp kết quả
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->orderBy('ngaycapnhat', 'DESC');
                    break;
                case 'oldest':
                    $query->orderBy('ngaycapnhat', 'ASC');
                    break;
                case 'name_asc':
                    $query->orderBy('title', 'ASC');
                    break;
                case 'name_desc':
                    $query->orderBy('title', 'DESC');
                    break;
                case 'views':
                    $query->orderBy('count_views', 'DESC');
                    break;
                default:
                    $query->orderBy('ngaycapnhat', 'DESC');
            }
        } else {
            // Mặc định sắp xếp theo ngày cập nhật mới nhất
            $query->orderBy('ngaycapnhat', 'DESC');
        }

        // BƯỚC 2: THỰC HIỆN TRUY VẤN VÀ PHÂN TRANG
        $movie = $query->paginate(10);

        // BƯỚC 3: XỬ LÝ YÊU CẦU AJAX
        if ($request->ajax() || $request->has('ajax')) {
            try {
                // Render HTML cho phần danh sách phim
                $movieHtml = view('pages.partials.movie-grid', compact('movie'))->render();

                // Render HTML cho phần phân trang
                $paginationHtml = $movie->appends(request()->query())->links("pagination::bootstrap-4")->toHtml();

                // Trả về JSON response
                return response()->json([
                    'status' => 'success',
                    'movieHtml' => $movieHtml,
                    'paginationHtml' => $paginationHtml,
                    'total' => $movie->total(),
                    'currentPage' => $movie->currentPage(),
                    'lastPage' => $movie->lastPage()
                ]);
            } catch (\Exception $e) {
                // Xử lý lỗi và trả về thông báo
                return response()->json([
                    'status' => 'error',
                    'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
                ], 500);
            }
        }

        // BƯỚC 4: LOAD DỮ LIỆU CHO TRANG ĐẦY ĐỦ (chỉ khi không phải AJAX)
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)
            ->where('status', 1)
            ->orderBy('ngaycapnhat', 'DESC')
            ->take(5)
            ->get();
        $phimhot_trailer = Movie::where('resolution', 5)
            ->where('status', 1)
            ->orderBy('ngaycapnhat', 'DESC')
            ->take(5)
            ->get();

        // BƯỚC 5: TRẢ VỀ VIEW ĐẦY ĐỦ
        return view('pages.advanced-filter', compact(
            'category',
            'genre',
            'country',
            'movie',
            'phimhot_sidebar',
            'phimhot_trailer'
        ));
    }
    public function timkiem()
    {
        // Kiểm tra nếu có tham số search
        if (!isset($_GET['search'])) {
            return redirect('/');
        }

        // Lấy từ khóa tìm kiếm
        $search = trim($_GET['search']);

        // Nếu search rỗng, chuyển hướng về trang chủ
        if (empty($search)) {
            return redirect('/');
        }

        // Lấy dữ liệu chung cần thiết cho view
        $category = Category::orderBy('position', 'ASC')
            ->where('status', 1)
            ->get();

        $genre = Genre::orderBy('id', 'DESC')->get();

        $country = Country::orderBy('id', 'DESC')->get();

        // Lấy phim hot cho sidebar
        $phimhot_sidebar = Movie::where('phim_hot', 1)
            ->where('status', 1)
            ->orderBy('ngaycapnhat', 'DESC')
            ->take(30)
            ->get();

        // Lấy phim trailer hot
        $phimhot_trailer = Movie::where('resolution', 5)
            ->where('status', 1)
            ->orderBy('ngaycapnhat', 'DESC')
            ->take(10)
            ->get();

        // Tìm kiếm phim theo tiêu đề
        $movie = Movie::where('title', 'LIKE', '%' . $search . '%')
            ->orderBy('ngaycapnhat', 'DESC')
            ->paginate(40);

        // Trả về view kết quả tìm kiếm
        return view('pages.timkiem', compact(
            'category',
            'genre',
            'country',
            'search',
            'movie',
            'phimhot_sidebar',
            'phimhot_trailer'
        ));
    }
    public function home()
    {
        $phimhot = Movie::WithCount('episode')->where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $category = Category::orderBy('position', 'ASC')->where('status', 1)->get();

        $category_home = Category::with(['movie' => function ($q) {
            $q->withCount('episode');
        }])
            ->orderBy('position', 'ASC')->where('status', 1)->get();
        return view('pages.home', compact('category', 'genre', 'country', 'category_home', 'phimhot', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function category($slug)
    {

        $category = Category::orderBy('position', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $cate_slug = Category::where('slug', $slug)->first();
        $movie = Movie::withCount('episode')->where('category_id', $cate_slug->id)->orderBy('ngaycapnhat', 'DESC')->paginate(25);
        return view('pages.category', compact('category', 'genre', 'country', 'cate_slug', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function year($year)
    {

        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $year = $year;
        $movie = Movie::withCount('episode')->where('year', $year)->orderBy('ngaycapnhat', 'DESC')->paginate(25);
        return view('pages.year', compact('category', 'genre', 'country', 'year', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function tag($tag)
    {

        $category = Category::orderBy('position', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $tag = $tag;
        $movie = Movie::withCount('episode')->where('tags', 'LIKE', '%' . $tag . '%')->orderBy('ngaycapnhat', 'DESC')->paginate(4);
        return view('pages.tag', compact('category', 'genre', 'country', 'tag', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function genre($slug)
    {
        $category = Category::orderBy('position', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $genre_slug = Genre::where('slug', $slug)->first();
        $movie_genre = Movie_Genre::where('genre_id', $genre_slug->id)->get();
        $many_genre = [];
        foreach ($movie_genre as $key => $movi) {
            $many_genre[] = $movi->movie_id;
        }
        $movie = Movie::withCount('episode')->whereIn('id', $many_genre)->orderBy('ngaycapnhat', 'DESC')->paginate(25);

        return view('pages.genre', compact('category', 'genre', 'country', 'genre_slug', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function country($slug)
    {
        $category = Category::orderBy('position', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $country_slug = Country::where('slug', $slug)->first();
        $movie = Movie::withCount('episode')->where('country_id', $country_slug->id)->orderBy('ngaycapnhat', 'DESC')->paginate(25);

        return view('pages.country', compact('category', 'genre', 'country', 'country_slug', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function movie($slug)
    {
        $category = Category::orderBy('position', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $movie = Movie::withCount('episode')->with('category', 'genre', 'country', 'movie_genre')->where('slug', $slug)->where('status', 1)->first();
        $related = Movie::withCount('episode')->with('category', 'genre', 'country')->where('category_id', $movie->category->id)->orderBy(DB::raw('RAND()'))->whereNotIn('slug', [$slug])->get();
        $episode_tapdau = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'asc')->first();
        //lay 3 tap gan nhat
        $episode = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'desc')->take(3)->get();
        //lay tong so tap
        $episode_current_list = Episode::with('movie')->where('movie_id', $movie->id)->get();
        $episode_current_list_count = $episode_current_list->count();
        //rating movie
        $rating = Rating::where('movie_id', $movie->id)->avg('rating');
        $rating = round($rating);
        $count_total = Rating::where('movie_id', $movie->id)->count();
        // lượt xem
        $count_views = $movie->count_views;


        return view('pages.movie', compact('category', 'genre', 'country', 'movie', 'related', 'phimhot_sidebar', 'phimhot_trailer', 'episode', 'episode_tapdau', 'episode_current_list_count', 'rating', 'count_total','count_views'));
    }
    //lượt xem phim
    public function incrementView(Request $request)
    {
        try {
            $movie_id = $request->input('movie_id');
            $movie = Movie::find($movie_id);

            if ($movie) {
                $movie->count_views = $movie->count_views + 1;
                $movie->save();

                // Cập nhật file JSON với lượt xem mới
                $this->updateMovieJsonViews($movie_id, $movie->count_views);

                return response()->json([
                    'status' => 'success',
                    'message' => 'View counted successfully',
                    'count_views' => $movie->count_views,
                    'movie_slug' => $movie->slug
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Movie not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Phương thức cập nhật lượt xem trong file JSON
    private function updateMovieJsonViews($movieId, $newViews)
    {
        $path = public_path("/json/movies.json");

        if (file_exists($path)) {
            $jsonData = json_decode(file_get_contents($path), true);

            foreach ($jsonData as $key => $movie) {
                if ($movie['id'] == $movieId) {
                    $jsonData[$key]['count_views'] = $newViews;
                    break;
                }
            }

            file_put_contents($path, json_encode($jsonData));
        }
    }


    public function add_rating(Request $request)
    {
        $data = $request->all();
        $ip_address = $request->ip();

        // Kiểm tra xem người dùng đã đánh giá phim này chưa
        $rating_count = Rating::where('movie_id', $data['movie_id'])
            ->where('ip_address', $ip_address)
            ->count();

        if ($rating_count > 0) {
            return 'exist';
        } else {
            // Thêm đánh giá mới
            $rating = new Rating();
            $rating->movie_id = $data['movie_id'];
            $rating->rating = $data['index'];
            $rating->ip_address = $ip_address;
            $rating->save();

            // Cập nhật lại giá trị đánh giá trung bình
            $avg_rating = Rating::where('movie_id', $data['movie_id'])->avg('rating');
            $avg_rating = round($avg_rating);

            // Cập nhật file JSON với rating mới
            $this->updateMovieJsonRating($data['movie_id'], $avg_rating);

            return 'done';
        }
    }

    // Hàm mới để cập nhật rating trong file JSON
    private function updateMovieJsonRating($movieId, $newRating)
    {
        $path = public_path("/json/movies.json");

        if (file_exists($path)) {
            $jsonData = json_decode(file_get_contents($path), true);

            foreach ($jsonData as $key => $movie) {
                if ($movie['id'] == $movieId) {
                    $jsonData[$key]['rating'] = $newRating;
                    $jsonData[$key]['rating_count'] = Rating::where('movie_id', $movieId)->count();
                    break;
                }
            }

            file_put_contents($path, json_encode($jsonData));
        }
    }


    public function watch($slug, $tap)
    {

        $category = Category::orderBy('position', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $movie = Movie::withCount('episode')->with('category', 'genre', 'country', 'movie_genre', 'episode')->where('slug', $slug)->where('status', 1)->first();
        $related = Movie::withCount('episode')->with('category', 'genre', 'country')->where('category_id', $movie->category->id)->orderBy(DB::raw('RAND()'))->whereNotIn('slug', [$slug])->get();
        // return response()->json($movie);
        if (isset($tap)) {

            $tapphim = $tap;
            $tapphim = substr($tap, 4, 20);
            $episode = Episode::where('movie_id', $movie->id)->where('episode', $tapphim)->first();
        } else {
            $tapphim = 1;
            $episode = Episode::where('movie_id', $movie->id)->where('episode', $tapphim)->first();
        }
        return view('pages.watch', compact('category', 'genre', 'country', 'movie', 'related', 'phimhot_sidebar', 'phimhot_trailer', 'episode', 'tapphim'));
    }
    public function episode()
    {
        return view('pages.episode');
    }
}