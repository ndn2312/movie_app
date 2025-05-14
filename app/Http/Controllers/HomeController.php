<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Movie;
use App\Models\Episode;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $statistics = $this->getStatistics();
        return view('home', compact('statistics'));
    }

    /**
     * Lấy thống kê dữ liệu cho dashboard
     *
     * @return array
     */
    public function getStatistics()
    {
        // Tổng số người dùng
        $totalUsers = User::count();
        
        // Tổng số phim
        $totalMovies = Movie::count();
        
        // Tổng số tập phim
        $totalEpisodes = Episode::count();
        
        // Tổng lượt xem phim
        $totalViews = Movie::sum('count_views');
        
        // Tổng số danh mục
        $totalCategories = Category::count();
        
        // Tổng số thể loại
        $totalGenres = Genre::count();
        
        // Tổng số quốc gia
        $totalCountries = Country::count();
        
        return [
            'total_users' => $totalUsers,
            'total_movies' => $totalMovies,
            'total_episodes' => $totalEpisodes,
            'total_views' => $totalViews,
            'total_categories' => $totalCategories,
            'total_genres' => $totalGenres,
            'total_countries' => $totalCountries
        ];
    }
}
