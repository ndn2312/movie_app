<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Thêm dòng này
use App\Http\Controllers\IndexController;
use App\Http\Controllers\HomeController;

//admin controller
use App\Http\Controllers\CategoryController;

use App\Http\Controllers\GenreController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\LeechMovieController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'home'])->name('homepage');

Route::get('/danh-muc/{slug}', [IndexController::class, 'category'])->name('category');
Route::get('/the-loai/{slug}', [IndexController::class, 'genre'])->name('genre');
Route::get('/quoc-gia/{slug}', [IndexController::class, 'country'])->name('country');
Route::get('/phim/{slug}', [IndexController::class, 'movie'])->name('movie');
Route::get('/xem-phim/{slug}/{tap}', [IndexController::class, 'watch'])->name('watch');
Route::get('/so-tap', [IndexController::class, 'episode'])->name('so-tap');
Route::get('/nam/{year}', [IndexController::class, 'year']);
Route::get('/tag/{tag}', [IndexController::class, 'tag']);
Route::get('/tim-kiem', [IndexController::class, 'timkiem'])->name('tim-kiem');
Route::get('/locphim', [IndexController::class, 'locphim'])->name('locphim');
Route::get('/loc-phim-nang-cao', [IndexController::class, 'advancedFilter'])->name('advancedfilter');
Route::post('/add-rating', [IndexController::class, 'add_rating'])->name('add-rating');
Route::post('/increment-view', 'App\Http\Controllers\IndexController@incrementView')->name('increment.view');



// Sử dụng Auth::routes với tham số chỉ định các routes cần thiết (không bao gồm reset mật khẩu)
Auth::routes(['reset' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Thêm route cho đổi mật khẩu
Route::get('change-password', 'App\Http\Controllers\ChangePasswordController@index')->name('password.change');
Route::post('change-password', 'App\Http\Controllers\ChangePasswordController@store')->name('password.update');

//route admin
Route::post('resorting', [CategoryController::class, 'resorting'])->name('resorting');
Route::resource('category', CategoryController::class);
Route::resource('genre', GenreController::class);
Route::resource('country', CountryController::class);
Route::get('add_episode/{id}', [EpisodeController::class, 'add_episode'])->name('add_episode');
Route::resource('episode', EpisodeController::class);
Route::get('select-movie', [EpisodeController::class, 'select_movie'])->name('select-movie');

Route::resource('movie', MovieController::class);
Route::post('movie-delete-multiple', [MovieController::class, 'deleteMultiple'])->name('movie.delete_multiple');

Route::get('/update-year-phim', [MovieController::class, 'update_year']);
Route::get('/update-topview-phim', [MovieController::class, 'update_topview']);
Route::post('/filter-topview-phim', [MovieController::class, 'filter_topview']);
Route::get('/filter-topview-default', [MovieController::class, 'filter_default']);
Route::post('/update-season-phim', [MovieController::class, 'update_season']);

Route::get('/category-choose', [MovieController::class, 'category_choose'])->name('category-choose');
Route::get('/country-choose', [MovieController::class, 'country_choose'])->name('country-choose');
Route::get('/phimhot-choose', [MovieController::class, 'phimhot_choose'])->name('phimhot-choose');
Route::get('/phude-choose', [MovieController::class, 'phude_choose'])->name('phude-choose');
Route::get('/status-choose', [MovieController::class, 'status_choose'])->name('status-choose');
Route::get('/resolution-choose', [MovieController::class, 'resolution_choose'])->name('resolution-choose');
Route::get('/thuocphim-choose', [MovieController::class, 'thuocphim_choose'])->name('thuocphim-choose');
Route::post('/update-image-movie-ajax', [MovieController::class, 'update_image_movie_ajax'])->name('update-image-movie-ajax');

//leech movie
Route::get('/leech-movie', [LeechMovieController::class, 'leech_movie'])->name('leech-movie');
Route::get('/leech-detail/{slug}', [LeechMovieController::class, 'leech_detail'])->name('leech-detail');
Route::post('/leech-store/{slug}', [LeechMovieController::class, 'leech_store'])->name('leech-store');
Route::post('/leech-store-multiple', [LeechMovieController::class, 'leech_store_multiple'])->name('leech-store-multiple');
Route::get('/leech-episode/{slug}', [LeechMovieController::class, 'leech_episode'])->name('leech-episode');
Route::post('/leech-episode-store/{slug}', [LeechMovieController::class, 'leech_episode_store'])->name('leech-episode-store');
Route::get('/leech-search', [LeechMovieController::class, 'search_movie'])->name('leech-search');
