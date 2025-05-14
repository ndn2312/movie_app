<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;
use App\Models\Category;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Episode;
use App\Models\LinkMovie;

use Carbon\Carbon;

class LeechMovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function leech_movie(Request $request)
    {
        // Kiểm tra có từ khóa tìm kiếm hay không
        $keyword = $request->input('keyword');

        if (!empty($keyword)) {
            // Chuyển hướng sang chức năng tìm kiếm nếu có từ khóa
            return redirect()->route('leech-search', $request->only(['keyword', 'sort_field', 'sort_type', 'sort_lang', 'category', 'country', 'year']));
        }

        // Lấy số trang từ request, mặc định là trang 1
        $page = $request->input('page', 1);

        // Lấy thông tin tổng số trang từ API (nếu API có cung cấp)
        $resp = Http::get('https://phimapi.com/danh-sach/phim-moi-cap-nhat-v3?page=' . $page)->json();

        // Lấy thông tin tổng số trang từ API (nếu có)
        $totalPages = $resp['pagination']['totalPages'] ?? 100; // Mặc định 100 trang nếu API không trả về

        // Truyền thêm thông tin về phân trang cho view
        return view('admincp.leech.index', compact('resp', 'page', 'totalPages'));
    }


    public function leech_episode($slug)
    {
        $resp = Http::get('https://phimapi.com/phim/' . $slug)->json();
        // $resp_movie[] = $resp['movie'];
        return view('admincp.leech.leech_episodes', compact('resp'));
    }

    public function leech_detail($slug)
    {
        $resp = Http::get('https://phimapi.com/phim/' . $slug)->json();
        $resp_movie[] = $resp['movie'];
        return view('admincp.leech.leech_detail', compact('resp_movie'));
    }
    public function leech_episode_store(Request $request, $slug)
    {
        $movie = Movie::where('slug', $slug)->first();
        $resp = Http::get('https://phimapi.com/phim/' . $slug)->json();
        $episode_count = 0;
        $selected_server = $request->input('server_name', null);

        foreach ($resp['episodes'] as $key => $res) {
            // Chỉ xử lý server được chọn
            if ($selected_server && $res['server_name'] !== $selected_server) {
                continue;
            }

            foreach ($res['server_data'] as $key_data => $res_data) {
                $ep = new Episode();
                $ep->movie_id = $movie->id;
                $ep->linkphim = '<div class="hls-media-container" data-media-provider="hls"><video crossorigin="anonymous" preload="auto" playsinline class="hls-video video-js"><source src=" ' . $res_data['link_m3u8'] . '" type="application/x-mpegurl"></video></div>';
                $ep->episode = $res_data['name'];
                $ep->server_name = $res['server_name']; // Lưu server_name
                $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
                $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
                $ep->save();
                $episode_count++;
            }
        }

        $server_info = $selected_server ? " (Server: $selected_server)" : "";

        return redirect()->route('episode.index')->with([
            'movie_title' => $movie->title . ' (' . $episode_count . ' tập phim)' . $server_info,
            'success_message' => 'đã được',
            'action_type' => 'thêm',
            'success_end' => 'thành công! ',
        ]);
    }

    public function leech_store(Request $request, $slug)
    {
        $resp = Http::get('https://phimapi.com/phim/' . $slug)->json();
        $resp_movie[] = $resp['movie'];
        $movie = new Movie();
        foreach ($resp_movie as $key => $res) {
            $movie->title = $res['name'];
            $movie->trailer = $res['trailer_url'];

            // Tạo tags thông minh
            $tags = $res['name'];
            if (isset($res['origin_name']) && !empty($res['origin_name'])) {
                $tags .= ',' . $res['origin_name'];
            }
            $tags .= ',' . $res['slug'];

            // Giới hạn danh sách diễn viên, chỉ lấy tối đa 5 diễn viên đầu tiên
            if (isset($res['actor']) && is_array($res['actor'])) {
                $limitedActors = array_slice($res['actor'], 0, 5);
                $tags .= ',' . implode(',', $limitedActors);
            }

            // Giới hạn độ dài của tags không quá 255 ký tự (hoặc theo giới hạn của cơ sở dữ liệu)
            $movie->tags = substr($tags, 0, 255);

            $movie->thoiluong = $res['time'];

            // Xác định chất lượng phim dựa vào trường quality từ API
            if (isset($res['quality'])) {
                switch (strtoupper($res['quality'])) {
                    case 'HD':
                        $movie->resolution = 0;
                        break;
                    case 'SD':
                        $movie->resolution = 1;
                        break;
                    case 'HDCAM':
                        $movie->resolution = 2;
                        break;
                    case 'CAM':
                        $movie->resolution = 3;
                        break;
                    case 'FHD':
                    case 'FULLHD':
                        $movie->resolution = 4;
                        break;
                    default:
                        $movie->resolution = 0;
                }
            } else {
                $movie->resolution = 0;
            }

            // Xác định phụ đề hoặc lồng tiếng
            if (isset($res['lang'])) {
                if (strpos($res['lang'], 'Thuyết Minh') !== false || strpos($res['lang'], 'Lồng Tiếng') !== false) {
                    $movie->phude = 1; // Thuyết minh
                } else {
                    $movie->phude = 0; // Phụ đề
                }
            } else {
                $movie->phude = 0;
            }

            $movie->name_eng = $res['origin_name'];
            $movie->phim_hot = 1;
            $movie->slug = $res['slug'];
            $movie->description = $res['content'];
            $movie->status = 1;

            // Xác định loại phim (phimbo/phimle) đúng cách
            if (isset($res['type'])) {
                if ($res['type'] == 'series' || $res['type'] == 'tvshows' || $res['type'] == 'hoathinh') {
                    $movie->thuocphim = 'phimbo';
                } else {
                    $movie->thuocphim = 'phimle';
                }
            } else if (isset($res['episode_total']) && !empty($res['episode_total']) && $res['episode_total'] > 1) {
                // Nếu có nhiều tập thì là phim bộ
                $movie->thuocphim = 'phimbo';
            } else if (isset($res['episode_current']) && preg_match('/Hoàn Tất|Full|Tập|\//', $res['episode_current'])) {
                // Nếu trạng thái tập có chữ Hoàn Tất, Full hoặc có số tập thì là phim bộ
                $movie->thuocphim = 'phimbo';
            } else {
                $movie->thuocphim = 'phimle';
            }

            // Lưu số tập cho phim bộ nếu là phim bộ
            if ($movie->thuocphim == 'phimbo') {
                if (isset($res['episode_total']) && !empty($res['episode_total'])) {
                    $movie->sotap = $res['episode_total'];
                } elseif (isset($res['episode_current'])) {
                    // Thử trích xuất số tập từ các định dạng khác nhau
                    if (preg_match('/\((\d+)\/(\d+)\)/', $res['episode_current'], $matches)) {
                        $movie->sotap = $matches[2]; // Lấy tổng số tập từ định dạng (x/y)
                    } elseif (preg_match('/Hoàn Tất\s+\((\d+)\/(\d+)\)/', $res['episode_current'], $matches)) {
                        $movie->sotap = $matches[2];
                    } elseif (preg_match('/(\d+)/', $res['episode_current'], $matches)) {
                        $movie->sotap = $matches[1]; // Lấy số đầu tiên tìm thấy
                    }
                }
            }

            // Lưu năm sản xuất
            if (isset($res['year']) && !empty($res['year'])) {
                $movie->year = $res['year'];
            }

            // Lưu thông tin diễn viên
            if (isset($res['actor']) && is_array($res['actor'])) {
                $movie->actors = implode(', ', $res['actor']);
            }

            // Lưu thông tin đạo diễn
            if (isset($res['director']) && is_array($res['director'])) {
                $movie->director = implode(', ', $res['director']);
            }

            // Lưu thông tin TMDB/IMDB nếu có
            if (isset($res['tmdb'])) {
                // Comment lại các trường có thể chưa tồn tại
                // if (isset($res['tmdb']['id'])) {
                //     $movie->tmdb_id = $res['tmdb']['id'];
                // }
                // if (isset($res['tmdb']['vote_average'])) {
                //     $movie->rating = $res['tmdb']['vote_average'];
                // }
                // if (isset($res['tmdb']['vote_count'])) {
                //     $movie->vote_count = $res['tmdb']['vote_count'];
                // }
                if (isset($res['tmdb']['season']) && $movie->thuocphim == 'phimbo') {
                    $movie->season = $res['tmdb']['season'];
                }
            }

            if (isset($res['imdb']) && isset($res['imdb']['id'])) {
                // $movie->imdb_id = $res['imdb']['id'];
                // Comment lại vì có thể chưa có trường imdb_id
            }

            // ĐỒNG BỘ DANH MỤC TỪ API
            // Bước 1: Lấy category từ API
            $categoryFromApi = null;
            if (isset($res['category']) && count($res['category']) > 0) {
                foreach ($res['category'] as $cat) {
                    // Ưu tiên danh mục cơ bản trước: Phim lẻ, Phim bộ, Phim chiếu rạp, v.v.
                    $baseCategoryNames = ['Phim Lẻ', 'Phim Bộ', 'Phim Chiếu Rạp', 'Phim Thuyết Minh', 'Phim Hoạt Hình'];
                    if (in_array($cat['name'], $baseCategoryNames)) {
                        $categoryFromApi = $cat['name'];
                        break;
                    }
                    // Nếu không tìm thấy danh mục cơ bản, lấy danh mục đầu tiên
                    $categoryFromApi = $res['category'][0]['name'];
                }
            }

            // Bước 2: Tìm category tương ứng trong database
            if (!empty($categoryFromApi)) {
                // Map tên danh mục API sang tên danh mục database
                $categoryMapping = [
                    'Phim Lẻ' => 'Phim lẻ',
                    'Phim Bộ' => 'Phim bộ',
                    'Phim Chiếu Rạp' => 'Phim chiếu rạp',
                    'Phim Hoạt Hình' => 'Phim hoạt hình',
                    'Phim Thuyết Minh' => 'Phim thuyết minh'
                ];

                $searchName = isset($categoryMapping[$categoryFromApi]) ?
                    $categoryMapping[$categoryFromApi] : $categoryFromApi;

                // Tìm category theo tên đã được map
                $existingCategory = Category::where('title', $searchName)
                    ->orWhere('title', 'like', $searchName . '%')
                    ->orWhere('title', 'like', '%' . $searchName . '%')
                    ->first();

                if ($existingCategory) {
                    $movie->category_id = $existingCategory->id;
                } else {
                    // Backup: Đồng bộ theo loại phim nếu không tìm thấy theo tên
                    if ($movie->thuocphim == 'phimbo') {
                        $backupCategory = Category::where('title', 'like', '%phim bộ%')->first();
                    } else {
                        $backupCategory = Category::where('title', 'like', '%phim lẻ%')->first();
                    }

                    if ($backupCategory) {
                        $movie->category_id = $backupCategory->id;
                    } else {
                        // Nếu không tìm thấy, lấy category đầu tiên
                        $category = Category::orderby('id', 'asc')->first();
                        $movie->category_id = $category->id;
                    }
                }
            } else {
                // Nếu không có thông tin category từ API
                if ($movie->thuocphim == 'phimbo') {
                    $defaultCategory = Category::where('title', 'like', '%phim bộ%')->first();
                } else {
                    $defaultCategory = Category::where('title', 'like', '%phim lẻ%')->first();
                }

                if ($defaultCategory) {
                    $movie->category_id = $defaultCategory->id;
                } else {
                    $category = Category::orderby('id', 'asc')->first();
                    $movie->category_id = $category->id;
                }
            }

            // Tìm và gán country dựa trên quốc gia từ API
            if (isset($res['country']) && count($res['country']) > 0) {
                // Thử nhiều quốc gia nếu quốc gia đầu tiên không khớp
                $foundCountry = false;
                foreach ($res['country'] as $apiCountry) {
                    $countryName = trim($apiCountry['name']);

                    // Tìm kiếm country theo tên chính xác hoặc tương đối
                    $existingCountry = Country::where('title', $countryName)
                        ->orWhere('title', 'like', '%' . $countryName . '%')
                        ->first();

                    if ($existingCountry) {
                        $movie->country_id = $existingCountry->id;
                        $foundCountry = true;
                        break;
                    }
                }

                // Nếu không tìm thấy country nào khớp, sử dụng mặc định
                if (!$foundCountry) {
                    $country = Country::orderby('id', 'desc')->first();
                    $movie->country_id = $country->id;
                }
            } else {
                $country = Country::orderby('id', 'desc')->first();
                $movie->country_id = $country->id;
            }

            $movie->count_views = 0;
            $movie->ngaytao = Carbon::now('Asia/Ho_Chi_Minh');
            $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
            $movie->image = $res['poster_url'];

            // // Lưu poster_url và thumb_url
            // if (isset($res['poster_url'])) {
            //     $movie->poster_url = $res['poster_url'];
            // }

            if (isset($res['thumb_url'])) {
                $movie->thumb_url = $res['thumb_url'];
            }

            // Thêm nhiều thể loại cho phim từ API
            if (isset($res['category']) && count($res['category']) > 0) {
                $genreIds = [];
                // Đặt một thể loại chính cho phim (genre_id)
                $primaryGenreSet = false;

                foreach ($res['category'] as $apiGenre) {
                    $genreName = trim($apiGenre['name']);

                    // Tìm kiếm genre theo tên chính xác ưu tiên trước
                    $existingGenre = Genre::where('title', $genreName)->first();

                    // Nếu không tìm thấy, tìm kiếm với điều kiện LIKE
                    if (!$existingGenre) {
                        $existingGenre = Genre::where('title', 'like', '%' . $genreName . '%')
                            ->first();
                    }

                    if ($existingGenre && !in_array($existingGenre->id, $genreIds)) {
                        $genreIds[] = $existingGenre->id;

                        // Nếu chưa set genre_id chính, set nó
                        if (!$primaryGenreSet) {
                            $movie->genre_id = $existingGenre->id;
                            $primaryGenreSet = true;
                        }
                    }
                }

                // Nếu không tìm thấy genre nào, sử dụng genre mặc định
                if (empty($genreIds)) {
                    $genre = Genre::orderby('id', 'desc')->first();
                    $movie->genre_id = $genre->id;
                    $genreIds[] = $genre->id;
                }

                // Lưu phim trước khi sync genres
                $movie->save();

                // Sync tất cả thể loại đã tìm thấy (thay vì attach)
                if (!empty($genreIds)) {
                    $movie->movie_genre()->sync($genreIds);
                }
            } else {
                // Nếu không có thể loại từ API, sử dụng thể loại mặc định
                $genre = Genre::orderby('id', 'desc')->first();
                $movie->genre_id = $genre->id;

                // Lưu phim trước khi sync genre
                $movie->save();

                $movie->movie_genre()->sync([$genre->id]);
            }

            return redirect()->route('movie.index')->with([
                'success_message' => 'đã được',
                'action_type' => 'thêm',
                'success_end' => 'thành công!',
                'movie_title' => $res['name']
            ]);
        }
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function leech_store_multiple(Request $request)
    {
        // Lấy danh sách slug phim đã chọn từ request
        $selectedMovies = json_decode($request->selected_movies, true);

        if (empty($selectedMovies)) {
            return redirect()->route('leech-movie')->with('error', 'Không có phim nào được chọn');
        }

        $successCount = 0;
        $failedCount = 0;
        $duplicateCount = 0;
        $successTitles = [];

        // Xử lý từng phim
        foreach ($selectedMovies as $slug) {
            try {
                // Kiểm tra xem phim đã tồn tại chưa trước khi gọi API
                $existingMovie = Movie::where('slug', $slug)->first();
                if ($existingMovie) {
                    $duplicateCount++;
                    continue; // Bỏ qua phim đã tồn tại
                }

                $resp = Http::get('https://phimapi.com/phim/' . $slug)->json();

                if (isset($resp['movie'])) {
                    $resp_movie = [$resp['movie']];
                    $movie = new Movie();

                    foreach ($resp_movie as $key => $res) {
                        // Copy từ đây, giữ nguyên nội dung xử lý phim
                        $movie->title = $res['name'];
                        $movie->trailer = $res['trailer_url'];

                        // Tạo tags thông minh
                        $tags = $res['name'];
                        if (isset($res['origin_name']) && !empty($res['origin_name'])) {
                            $tags .= ',' . $res['origin_name'];
                        }
                        $tags .= ',' . $res['slug'];

                        // Giới hạn danh sách diễn viên, chỉ lấy tối đa 5 diễn viên đầu tiên
                        if (isset($res['actor']) && is_array($res['actor'])) {
                            $limitedActors = array_slice($res['actor'], 0, 5);
                            $tags .= ',' . implode(',', $limitedActors);
                        }

                        // Giới hạn độ dài của tags không quá 255 ký tự (hoặc theo giới hạn của cơ sở dữ liệu)
                        $movie->tags = substr($tags, 0, 255);

                        $movie->thoiluong = $res['time'];

                        // Xác định chất lượng phim dựa vào trường quality từ API
                        if (isset($res['quality'])) {
                            switch (strtoupper($res['quality'])) {
                                case 'HD':
                                    $movie->resolution = 0;
                                    break;
                                case 'SD':
                                    $movie->resolution = 1;
                                    break;
                                case 'HDCAM':
                                    $movie->resolution = 2;
                                    break;
                                case 'CAM':
                                    $movie->resolution = 3;
                                    break;
                                case 'FHD':
                                case 'FULLHD':
                                    $movie->resolution = 4;
                                    break;
                                default:
                                    $movie->resolution = 0;
                            }
                        } else {
                            $movie->resolution = 0;
                        }

                        // Xác định phụ đề hoặc lồng tiếng
                        if (isset($res['lang'])) {
                            if (strpos($res['lang'], 'Thuyết Minh') !== false || strpos($res['lang'], 'Lồng Tiếng') !== false) {
                                $movie->phude = 1; // Thuyết minh
                            } else {
                                $movie->phude = 0; // Phụ đề
                            }
                        } else {
                            $movie->phude = 0;
                        }

                        $movie->name_eng = $res['origin_name'];
                        $movie->phim_hot = 1;
                        $movie->slug = $res['slug'];
                        $movie->description = $res['content'];
                        $movie->status = 1;

                        // Xác định loại phim (phimbo/phimle) đúng cách
                        if (isset($res['type'])) {
                            if ($res['type'] == 'series' || $res['type'] == 'tvshows' || $res['type'] == 'hoathinh') {
                                $movie->thuocphim = 'phimbo';
                            } else {
                                $movie->thuocphim = 'phimle';
                            }
                        } else if (isset($res['episode_total']) && !empty($res['episode_total']) && $res['episode_total'] > 1) {
                            // Nếu có nhiều tập thì là phim bộ
                            $movie->thuocphim = 'phimbo';
                        } else if (isset($res['episode_current']) && preg_match('/Hoàn Tất|Full|Tập|\//', $res['episode_current'])) {
                            // Nếu trạng thái tập có chữ Hoàn Tất, Full hoặc có số tập thì là phim bộ
                            $movie->thuocphim = 'phimbo';
                        } else {
                            $movie->thuocphim = 'phimle';
                        }

                        // Lưu số tập cho phim bộ nếu là phim bộ
                        if ($movie->thuocphim == 'phimbo') {
                            if (isset($res['episode_total']) && !empty($res['episode_total'])) {
                                $movie->sotap = $res['episode_total'];
                            } elseif (isset($res['episode_current'])) {
                                // Thử trích xuất số tập từ các định dạng khác nhau
                                if (preg_match('/\((\d+)\/(\d+)\)/', $res['episode_current'], $matches)) {
                                    $movie->sotap = $matches[2]; // Lấy tổng số tập từ định dạng (x/y)
                                } elseif (preg_match('/Hoàn Tất\s+\((\d+)\/(\d+)\)/', $res['episode_current'], $matches)) {
                                    $movie->sotap = $matches[2];
                                } elseif (preg_match('/(\d+)/', $res['episode_current'], $matches)) {
                                    $movie->sotap = $matches[1]; // Lấy số đầu tiên tìm thấy
                                }
                            }
                        }

                        // Lưu năm sản xuất
                        if (isset($res['year']) && !empty($res['year'])) {
                            $movie->year = $res['year'];
                        }

                        // Lưu thông tin diễn viên
                        if (isset($res['actor']) && is_array($res['actor'])) {
                            $movie->actors = implode(', ', $res['actor']);
                        }

                        // Lưu thông tin đạo diễn
                        if (isset($res['director']) && is_array($res['director'])) {
                            $movie->director = implode(', ', $res['director']);
                        }

                        // Lưu thông tin TMDB/IMDB nếu có
                        if (isset($res['tmdb'])) {
                            if (isset($res['tmdb']['season']) && $movie->thuocphim == 'phimbo') {
                                $movie->season = $res['tmdb']['season'];
                            }
                        }

                        // ĐỒNG BỘ DANH MỤC TỪ API
                        // Bước 1: Lấy category từ API
                        $categoryFromApi = null;
                        if (isset($res['category']) && count($res['category']) > 0) {
                            foreach ($res['category'] as $cat) {
                                // Ưu tiên danh mục cơ bản trước: Phim lẻ, Phim bộ, Phim chiếu rạp, v.v.
                                $baseCategoryNames = ['Phim Lẻ', 'Phim Bộ', 'Phim Chiếu Rạp', 'Phim Thuyết Minh', 'Phim Hoạt Hình'];
                                if (in_array($cat['name'], $baseCategoryNames)) {
                                    $categoryFromApi = $cat['name'];
                                    break;
                                }
                                // Nếu không tìm thấy danh mục cơ bản, lấy danh mục đầu tiên
                                $categoryFromApi = $res['category'][0]['name'];
                            }
                        }

                        // Bước 2: Tìm category tương ứng trong database
                        if (!empty($categoryFromApi)) {
                            // Map tên danh mục API sang tên danh mục database
                            $categoryMapping = [
                                'Phim Lẻ' => 'Phim lẻ',
                                'Phim Bộ' => 'Phim bộ',
                                'Phim Chiếu Rạp' => 'Phim chiếu rạp',
                                'Phim Hoạt Hình' => 'Phim hoạt hình',
                                'Phim Thuyết Minh' => 'Phim thuyết minh'
                            ];

                            $searchName = isset($categoryMapping[$categoryFromApi]) ?
                                $categoryMapping[$categoryFromApi] : $categoryFromApi;

                            // Tìm category theo tên đã được map
                            $existingCategory = Category::where('title', $searchName)
                                ->orWhere('title', 'like', $searchName . '%')
                                ->orWhere('title', 'like', '%' . $searchName . '%')
                                ->first();

                            if ($existingCategory) {
                                $movie->category_id = $existingCategory->id;
                            } else {
                                // Backup: Đồng bộ theo loại phim nếu không tìm thấy theo tên
                                if ($movie->thuocphim == 'phimbo') {
                                    $backupCategory = Category::where('title', 'like', '%phim bộ%')->first();
                                } else {
                                    $backupCategory = Category::where('title', 'like', '%phim lẻ%')->first();
                                }

                                if ($backupCategory) {
                                    $movie->category_id = $backupCategory->id;
                                } else {
                                    // Nếu không tìm thấy, lấy category đầu tiên
                                    $category = Category::orderby('id', 'asc')->first();
                                    $movie->category_id = $category->id;
                                }
                            }
                        } else {
                            // Nếu không có thông tin category từ API
                            if ($movie->thuocphim == 'phimbo') {
                                $defaultCategory = Category::where('title', 'like', '%phim bộ%')->first();
                            } else {
                                $defaultCategory = Category::where('title', 'like', '%phim lẻ%')->first();
                            }

                            if ($defaultCategory) {
                                $movie->category_id = $defaultCategory->id;
                            } else {
                                $category = Category::orderby('id', 'asc')->first();
                                $movie->category_id = $category->id;
                            }
                        }

                        // Tìm và gán country dựa trên quốc gia từ API
                        if (isset($res['country']) && count($res['country']) > 0) {
                            // Thử nhiều quốc gia nếu quốc gia đầu tiên không khớp
                            $foundCountry = false;
                            foreach ($res['country'] as $apiCountry) {
                                $countryName = trim($apiCountry['name']);

                                // Tìm kiếm country theo tên chính xác hoặc tương đối
                                $existingCountry = Country::where('title', $countryName)
                                    ->orWhere('title', 'like', '%' . $countryName . '%')
                                    ->first();

                                if ($existingCountry) {
                                    $movie->country_id = $existingCountry->id;
                                    $foundCountry = true;
                                    break;
                                }
                            }

                            // Nếu không tìm thấy country nào khớp, sử dụng mặc định
                            if (!$foundCountry) {
                                $country = Country::orderby('id', 'desc')->first();
                                $movie->country_id = $country->id;
                            }
                        } else {
                            $country = Country::orderby('id', 'desc')->first();
                            $movie->country_id = $country->id;
                        }

                        $movie->count_views = 0;
                        $movie->ngaytao = Carbon::now('Asia/Ho_Chi_Minh');
                        $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
                        $movie->image = $res['poster_url'];

                        if (isset($res['thumb_url'])) {
                            $movie->thumb_url = $res['thumb_url'];
                        }

                        // Thêm nhiều thể loại cho phim từ API
                        if (isset($res['category']) && count($res['category']) > 0) {
                            $genreIds = [];
                            // Đặt một thể loại chính cho phim (genre_id)
                            $primaryGenreSet = false;

                            foreach ($res['category'] as $apiGenre) {
                                $genreName = trim($apiGenre['name']);

                                // Tìm kiếm genre theo tên chính xác ưu tiên trước
                                $existingGenre = Genre::where('title', $genreName)->first();

                                // Nếu không tìm thấy, tìm kiếm với điều kiện LIKE
                                if (!$existingGenre) {
                                    $existingGenre = Genre::where('title', 'like', '%' . $genreName . '%')
                                        ->first();
                                }

                                if ($existingGenre && !in_array($existingGenre->id, $genreIds)) {
                                    $genreIds[] = $existingGenre->id;

                                    // Nếu chưa set genre_id chính, set nó
                                    if (!$primaryGenreSet) {
                                        $movie->genre_id = $existingGenre->id;
                                        $primaryGenreSet = true;
                                    }
                                }
                            }

                            // Nếu không tìm thấy genre nào, sử dụng genre mặc định
                            if (empty($genreIds)) {
                                $genre = Genre::orderby('id', 'desc')->first();
                                $movie->genre_id = $genre->id;
                                $genreIds[] = $genre->id;
                            }

                            // Lưu phim trước khi sync genres
                            $movie->save();

                            // Sync tất cả thể loại đã tìm thấy (thay vì attach)
                            if (!empty($genreIds)) {
                                $movie->movie_genre()->sync($genreIds);
                            }

                            $successCount++;

                            // Chỉ lưu tên cho 5 phim đầu tiên để tránh thông báo quá dài
                            if (count($successTitles) < 5) {
                                $successTitles[] = mb_substr($res['name'], 0, 30, 'UTF-8') . (mb_strlen($res['name'], 'UTF-8') > 30 ? '...' : '');
                            }
                        } else {
                            // Nếu không có thể loại từ API, sử dụng thể loại mặc định
                            $genre = Genre::orderby('id', 'desc')->first();
                            $movie->genre_id = $genre->id;

                            // Lưu phim trước khi sync genre
                            $movie->save();

                            $movie->movie_genre()->sync([$genre->id]);

                            $successCount++;

                            // Chỉ lưu tên cho 5 phim đầu tiên để tránh thông báo quá dài
                            if (count($successTitles) < 5) {
                                $successTitles[] = mb_substr($res['name'], 0, 30, 'UTF-8') . (mb_strlen($res['name'], 'UTF-8') > 30 ? '...' : '');
                            }
                        }
                    }
                } else {
                    $failedCount++;
                }
            } catch (\Exception $e) {
                $failedCount++;
                \Illuminate\Support\Facades\Log::error('Lỗi khi thêm phim ' . $slug . ': ' . $e->getMessage());
            }
        }

        // Tạo thông báo ngắn gọn và chính xác
        $message = '';

        if ($successCount > 0) {
            $message = 'Đã thêm thành công ' . $successCount . ' phim';
        }

        if ($duplicateCount > 0) {
            $message .= ($message ? ', ' : 'Có ') . $duplicateCount . ' phim đã tồn tại';
        }

        if ($failedCount > 0) {
            $message .= ($message ? ', ' : 'Có ') . $failedCount . ' phim thêm thất bại';
        }

        // Tạo thông báo danh sách phim đã thêm
        $titleMessage = '';
        if (!empty($successTitles)) {
            $titleMessage = implode(', ', $successTitles);
            if ($successCount > count($successTitles)) {
                $titleMessage .= ' và ' . ($successCount - count($successTitles)) . ' phim khác';
            }
        }

        // Điều chỉnh redirect sử dụng flash session hiệu quả hơn
        if ($successCount > 0) {
            return redirect()->route('movie.index')->with([
                'success_message' => 'đã được',
                'action_type' => 'thêm',
                'success_end' => 'thành công! ' . $message,
                'movie_title' => $titleMessage ?: ('Tổng cộng ' . $successCount . ' phim')
            ]);
        } else {
            return redirect()->route('leech-movie')->with('error', $message ?: 'Không có phim nào được thêm');
        }
    }

    public function search_movie(Request $request)
    {
        // Lấy từ khóa tìm kiếm
        $keyword = $request->input('keyword', '');
        $page = $request->input('page', 1);

        // Các tham số lọc (nếu có)
        $sort_field = $request->input('sort_field', 'modified.time');
        $sort_type = $request->input('sort_type', 'desc');
        $sort_lang = $request->input('sort_lang', '');
        $category = $request->input('category', '');
        $country = $request->input('country', '');
        $year = $request->input('year', '');
        $limit = $request->input('limit', '150');

        // Xây dựng URL API tìm kiếm
        $apiUrl = "https://phimapi.com/v1/api/tim-kiem?keyword={$keyword}&page={$page}";


        // Thêm các tham số lọc vào URL nếu có
        if (!empty($sort_field)) {
            $apiUrl .= "&sort_field={$sort_field}";
        }
        if (!empty($sort_type)) {
            $apiUrl .= "&sort_type={$sort_type}";
        }
        if (!empty($sort_lang)) {
            $apiUrl .= "&sort_lang={$sort_lang}";
        }
        if (!empty($category)) {
            $apiUrl .= "&category={$category}";
        }
        if (!empty($country)) {
            $apiUrl .= "&country={$country}";
        }
        if (!empty($year)) {
            $apiUrl .= "&year={$year}";
        }
        if (!empty($limit)) {
            $apiUrl .= "&limit={$limit}";
        }

        // Gọi API và lấy kết quả
        $resp = Http::get($apiUrl)->json();

        // Lấy thông tin tổng số trang từ API (nếu có)
        $totalPages = $resp['data']['params']['pagination']['totalPages'] ?? 1;
        $totalItems = $resp['data']['params']['pagination']['totalItems'] ?? 0;

        // Truyền dữ liệu cho view
        return view('admincp.leech.search', compact('resp', 'page', 'totalPages', 'keyword', 'totalItems', 'sort_field', 'sort_type', 'sort_lang', 'category', 'country', 'year', 'limit'));
    }
}
