<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Movie;
use App\Models\Episode;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Chia sẻ biến genre, country và category tới tất cả các view
        View::composer('*', function ($view) {
            $genre = Genre::orderBy('id', 'DESC')->get();
            $country = Country::orderBy('id', 'DESC')->get();
            $category = Category::orderBy('id', 'DESC')->get();

            $view->with(compact('genre', 'country', 'category'));
        });

        View::composer('layouts.app', function ($view) {
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

            $statistics = [
                'total_users' => $totalUsers,
                'total_movies' => $totalMovies,
                'total_episodes' => $totalEpisodes,
                'total_views' => $totalViews,
                'total_categories' => $totalCategories,
                'total_genres' => $totalGenres,
                'total_countries' => $totalCountries
            ];

            $view->with('statistics', $statistics);
        });
    }
}
