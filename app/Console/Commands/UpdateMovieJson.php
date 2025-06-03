<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use App\Models\Rating;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;

class UpdateMovieJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:movie-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update movies JSON file with latest data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Updating movie JSON file...');

        // Lấy danh sách phim mới nhất với thông tin đã cập nhật
        $list = Movie::with('category', 'movie_genre', 'country', 'genre')
            ->withCount('episode')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($movie) {
                // Tính toán rating trung bình cho mỗi phim
                $avgRating = Rating::where('movie_id', $movie->id)->avg('rating');
                $ratingCount = Rating::where('movie_id', $movie->id)->count();

                // Thêm thông tin vào đối tượng phim
                $movie->rating = round($avgRating, 1) ?: 0;
                $movie->rating_count = $ratingCount;

                return $movie;
            });

        // Đảm bảo thư mục tồn tại
        $path = public_path("/json/");
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // Ghi dữ liệu mới vào file JSON với options đảm bảo encoding đúng
        File::put(
            $path . 'movies.json',
            json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $this->info('Movie JSON file has been updated successfully!');

        return 0;
    }
}
