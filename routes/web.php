<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\HomeController;

// Admin controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\LeechMovieController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - Public Routes (không cần đăng nhập)
|--------------------------------------------------------------------------
*/

// Trang chủ và các trang công khai
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
Route::post('/filter-topview-phim', [MovieController::class, 'filter_topview']);
Route::get('/filter-topview-default', [MovieController::class, 'filter_default']);

// Routes cho bình luận
Route::post('/comment/store', [App\Http\Controllers\CommentController::class, 'store'])->name('comment.store')->middleware('auth');
Route::post('/comment/update/{id}', [App\Http\Controllers\CommentController::class, 'update'])->name('comment.update')->middleware('auth');
Route::post('/comment/delete/{id}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comment.delete')->middleware('auth');

// Route xác thực người dùng
Auth::routes(['login' => false, 'register' => false, 'reset' => false]);

// Routes cho Modal AJAX login/register
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.post');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

// Chuyển hướng khi người dùng truy cập vào /login
Route::get('/login', function () {
    // Kiểm tra nếu người dùng đã đăng nhập
    if (Auth::check()) {
        return redirect()->route('homepage');
    }
    // Nếu chưa đăng nhập, chuyển hướng đến trang chủ với yêu cầu hiển thị form đăng nhập
    return redirect()->route('homepage', ['show_login' => 'true']);
});

/*
|--------------------------------------------------------------------------
| User Routes - Cần đăng nhập (User + Admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Trang yêu thích
    Route::get('/favorites', [App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/add', [App\Http\Controllers\FavoriteController::class, 'store'])->name('favorites.add');
    Route::delete('/favorites/remove/{id}', [App\Http\Controllers\FavoriteController::class, 'destroy'])->name('favorites.remove');
    Route::get('/favorites/check', [App\Http\Controllers\FavoriteController::class, 'check'])->name('favorites.check');

    // ChatBot AI
    Route::get('/chatbot', [App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot');
    Route::post('/chatbot/send', [App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');
    Route::post('/chatbot/clear', [App\Http\Controllers\ChatbotController::class, 'clearHistory'])->name('chatbot.clear');

    // Lưu và lấy lịch sử xem phim
    Route::post('/history/save', [App\Http\Controllers\WatchHistoryController::class, 'store'])->name('history.save');
    Route::get('/history/get/{movie_id}/{episode?}', [App\Http\Controllers\WatchHistoryController::class, 'getHistory'])->name('history.get');
    Route::get('/history', [App\Http\Controllers\WatchHistoryController::class, 'index'])->name('history');
    Route::delete('/history/remove/{id}', [App\Http\Controllers\WatchHistoryController::class, 'destroy'])->name('history.remove');
    Route::delete('/history/clear', [App\Http\Controllers\WatchHistoryController::class, 'clearAll'])->name('history.clear');

    // Trang thông tin tài khoản
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account');
    Route::post('/account/update', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
    Route::post('/account/change-password', [App\Http\Controllers\AccountController::class, 'changePassword'])->name('account.change-password');
    Route::post('/account/upload-avatar', [App\Http\Controllers\AccountController::class, 'uploadAvatar'])->name('account.upload-avatar');

    // Routes cho thông báo
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/mark-as-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/delete/{id}', [App\Http\Controllers\NotificationController::class, 'deleteNotification'])->name('notifications.delete');
    Route::delete('/notifications/delete-all', [App\Http\Controllers\NotificationController::class, 'deleteAllNotifications'])->name('notifications.delete-all');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - Chỉ dành cho Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    // Thay đổi mật khẩu admin
    Route::get('/change-password', 'App\Http\Controllers\ChangePasswordController@index')->name('password.change');
    Route::post('/change-password', 'App\Http\Controllers\ChangePasswordController@store')->name('password.update');

    // Quản lý danh mục
    Route::post('/resorting', [CategoryController::class, 'resorting'])->name('resorting');
    Route::resource('/category', CategoryController::class);

    // Quản lý thể loại
    Route::resource('/genre', GenreController::class);

    // Quản lý quốc gia
    Route::resource('/country', CountryController::class);

    // Quản lý tập phim
    Route::get('/add_episode/{id}', [EpisodeController::class, 'add_episode'])->name('add_episode');
    Route::resource('/episode', EpisodeController::class);
    Route::post('/episode-delete-multiple', [EpisodeController::class, 'deleteMultiple'])->name('episode.delete_multiple');
    Route::get('/episode-get-all-ids', [EpisodeController::class, 'getAllIds'])->name('episode.get_all_ids');
    Route::get('/select-movie', [EpisodeController::class, 'select_movie'])->name('select-movie');

    // Quản lý phim
    Route::resource('/movie', MovieController::class);
    Route::post('/movie-delete-multiple', [MovieController::class, 'deleteMultiple'])->name('movie.delete_multiple');
    Route::get('/update-year-phim', [MovieController::class, 'update_year']);
    Route::get('/update-topview-phim', [MovieController::class, 'update_topview']);

    // Quản lý người dùng
    Route::resource('/user', UserController::class)->except(['create', 'store']);
    Route::post('/user-delete-multiple', [UserController::class, 'deleteMultiple'])->name('user.delete_multiple');
    Route::post('/user-change-status', [UserController::class, 'changeStatus'])->name('user.change_status');

    Route::post('/update-season-phim', [MovieController::class, 'update_season']);

    // Chức năng phụ trợ cho phim
    Route::get('/category-choose', [MovieController::class, 'category_choose'])->name('category-choose');
    Route::get('/country-choose', [MovieController::class, 'country_choose'])->name('country-choose');
    Route::get('/phimhot-choose', [MovieController::class, 'phimhot_choose'])->name('phimhot-choose');
    Route::get('/phude-choose', [MovieController::class, 'phude_choose'])->name('phude-choose');
    Route::get('/status-choose', [MovieController::class, 'status_choose'])->name('status-choose');
    Route::get('/resolution-choose', [MovieController::class, 'resolution_choose'])->name('resolution-choose');
    Route::get('/thuocphim-choose', [MovieController::class, 'thuocphim_choose'])->name('thuocphim-choose');
    Route::post('/update-image-movie-ajax', [MovieController::class, 'update_image_movie_ajax'])->name('update-image-movie-ajax');

    // Công cụ leech phim
    Route::get('/leech-movie', [LeechMovieController::class, 'leech_movie'])->name('leech-movie');
    Route::get('/leech-detail/{slug}', [LeechMovieController::class, 'leech_detail'])->name('leech-detail');
    Route::post('/leech-store/{slug}', [LeechMovieController::class, 'leech_store'])->name('leech-store');
    Route::post('/leech-store-multiple', [LeechMovieController::class, 'leech_store_multiple'])->name('leech-store-multiple');
    Route::get('/leech-episode/{slug}', [LeechMovieController::class, 'leech_episode'])->name('leech-episode');
    Route::post('/leech-episode-store/{slug}', [LeechMovieController::class, 'leech_episode_store'])->name('leech-episode-store');
    Route::get('/leech-search', [LeechMovieController::class, 'search_movie'])->name('leech-search');
    Route::post('/leech-episode-multiple', [LeechMovieController::class, 'leech_episode_multiple'])->name('leech-episode-multiple');
    Route::get('/api-list', [LeechMovieController::class, 'api_list'])->name('api-list');
});

// Route redirect cho trang admin cũ
Route::get('/home', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('home');
    }
    return redirect()->route('homepage');
});

// Thêm route mới cho trang đăng nhập admin
Route::get('/admin/login', function () {
    // Nếu đã đăng nhập
    if (Auth::check()) {
        // Nếu là admin, chuyển hướng đến dashboard
        if (Auth::user()->role === 'admin') {
            return redirect()->route('home');
        }
        // Nếu là user thường, chỉ chuyển hướng về trang chủ không đăng xuất
        return redirect()->route('homepage')
            ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
    }

    // Nếu chưa đăng nhập, chuyển hướng về trang chủ với form đăng nhập
    return redirect()->route('homepage', ['show_login' => 'true'])
        ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
})->name('admin.login');

// Định nghĩa route xử lý đăng nhập admin
Route::post('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'adminLogin'])->name('admin.login.post');

// Xử lý đường dẫn /admin (không có path phụ)
Route::get('/admin', function () {
    // Nếu đã đăng nhập
    if (Auth::check()) {
        // Nếu là admin, chuyển hướng đến dashboard
        if (Auth::user()->role === 'admin') {
            return redirect()->route('home');
        }
        // Nếu là user thường, chuyển hướng về trang chủ
        return redirect()->route('homepage')
            ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
    }
    // Nếu chưa đăng nhập, chuyển hướng về trang chủ với form đăng nhập
    return redirect()->route('homepage', ['show_login' => 'true'])
        ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
});

// Chặn người dùng thường (không phải admin) truy cập vào bất kỳ đường dẫn nào có /admin
Route::get('/admin/{any}', function () {
    // Nếu đã đăng nhập
    if (Auth::check()) {
        // Nếu không phải admin, chuyển hướng về trang chủ
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homepage')
                ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }
    } else {
        // Nếu chưa đăng nhập, chuyển hướng về trang chủ với tham số hiển thị form đăng nhập
        return redirect()->route('homepage', ['show_login' => 'true'])
            ->with('error', 'Vui lòng đăng nhập để tiếp tục.');
    }

    // Nếu là admin đã đăng nhập, cho phép tiếp tục (sẽ được xử lý bởi route khác)
    return redirect()->route('home');
})->where('any', '.*')->middleware('web');

// Route để làm mới CSRF token
Route::get('/refresh-csrf', function () {
    return csrf_token();
})->name('refresh-csrf');
