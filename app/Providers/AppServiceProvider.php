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

        // Chia sẻ biến genre, country, category cho tất cả các view
        // \Illuminate\Support\Facades\View::composer('*', function ($view) {
        //     $view->with([
        //         'genre' => \App\Models\Genre::orderBy('id', 'DESC')->get(),
        //         'country' => \App\Models\Country::orderBy('id', 'DESC')->get(),
        //         'category' => \App\Models\Category::orderBy('position', 'ASC')->get(),
        //     ]);
        // });
    }
}
