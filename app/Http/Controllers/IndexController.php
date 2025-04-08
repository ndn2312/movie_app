<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Movie;
use App\Models\Episode;
use App\Models\Movie_Genre;
use Illuminate\Support\Facades\DB;




class IndexController extends Controller
{
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

        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $cate_slug = Category::where('slug', $slug)->first();
        $movie = Movie::withCount('episode')->where('category_id', $cate_slug->id)->orderBy('ngaycapnhat', 'DESC')->paginate(4);
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
        $movie = Movie::withCount('episode')->where('year', $year)->orderBy('ngaycapnhat', 'DESC')->paginate(4);
        return view('pages.year', compact('category', 'genre', 'country', 'year', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function tag($tag)
    {

        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
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
        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
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
        $movie = Movie::withCount('episode')->whereIn('id', $many_genre)->orderBy('ngaycapnhat', 'DESC')->paginate(4);

        return view('pages.genre', compact('category', 'genre', 'country', 'genre_slug', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function country($slug)
    {
        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $country_slug = Country::where('slug', $slug)->first();
        $movie = Movie::withCount('episode')->where('country_id', $country_slug->id)->orderBy('ngaycapnhat', 'DESC')->paginate(4);

        return view('pages.country', compact('category', 'genre', 'country', 'country_slug', 'movie', 'phimhot_sidebar', 'phimhot_trailer'));
    }
    public function movie($slug)
    {
        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $movie = Movie::with('category', 'genre', 'country', 'movie_genre')->where('slug', $slug)->where('status', 1)->first();
        $related = Movie::with('category', 'genre', 'country')->where('category_id', $movie->category->id)->orderBy(DB::raw('RAND()'))->whereNotIn('slug', [$slug])->get();
        $episode_tapdau = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'asc')->first();
        //lay 3 tap gan nhat
        $episode = Episode::with('movie')->where('movie_id', $movie->id)->orderBy('episode', 'desc')->take(3)->get();
        //lay tong so tap
        $episode_current_list = Episode::with('movie')->where('movie_id', $movie->id)->get();
        $episode_current_list_count = $episode_current_list->count();
        return view('pages.movie', compact('category', 'genre', 'country', 'movie', 'related', 'phimhot_sidebar', 'phimhot_trailer', 'episode', 'episode_tapdau', 'episode_current_list_count'));
    }
    public function watch($slug, $tap)
    {

        $category = Category::orderBy('id', 'DESC')->where('status', 1)->get();
        $genre = Genre::orderBy('id', 'DESC')->get();
        $country = Country::orderBy('id', 'DESC')->get();
        $phimhot_sidebar = Movie::where('phim_hot', 1)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();
        $phimhot_trailer = Movie::where('resolution', 5)->where('status', 1)->orderBy('ngaycapnhat', 'DESC')->take('5')->get();

        $movie = Movie::with('category', 'genre', 'country', 'movie_genre', 'episode')->where('slug', $slug)->where('status', 1)->first();
        $related = Movie::with('category', 'genre', 'country')->where('category_id', $movie->category->id)->orderBy(DB::raw('RAND()'))->whereNotIn('slug', [$slug])->get();
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