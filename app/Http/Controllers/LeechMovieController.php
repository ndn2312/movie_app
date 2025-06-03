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

    public function leech_episode_multiple(Request $request)
    {
        // Lấy danh sách slug các phim đã chọn từ request
        $selectedMovies = json_decode($request->movie_slugs, true);

        if (empty($selectedMovies)) {
            return redirect()->route('movie.index')->with('error', 'Không có phim nào được chọn');
        }

        // Tăng thời gian thực thi tối đa lên 5 phút
        set_time_limit(300);

        // Chia nhỏ danh sách thành các batch để xử lý
        $batchSize = 5; // Số lượng phim xử lý mỗi batch
        $batches = array_chunk($selectedMovies, $batchSize);

        $successCount = 0;
        $errorCount = 0;
        $movieNames = [];
        $episodesAdded = 0;
        $processedSlugs = [];

        // Xử lý từng batch phim
        foreach ($batches as $batchIndex => $batchSlugs) {
            \Illuminate\Support\Facades\Log::info('Đang xử lý batch ' . ($batchIndex + 1) . '/' . count($batches));

            // Chuẩn bị các promise để gọi API song song cho mỗi batch
            $promises = [];
            $validMovies = [];

            // Kiểm tra phim đã tồn tại trước khi gọi API
            foreach ($batchSlugs as $slug) {
                // Bỏ qua slug trùng lặp hoặc không hợp lệ
                if (empty($slug) || in_array($slug, $processedSlugs)) {
                    continue;
                }

                $processedSlugs[] = $slug;

                // Kiểm tra phim có tồn tại trong DB không
                $movie = Movie::where('slug', $slug)->first();
                if (!$movie) {
                    $errorCount++;
                    continue;
                }

                $validMovies[$slug] = $movie;
                $promises[$slug] = \Illuminate\Support\Facades\Http::async()->get('https://phimapi.com/phim/' . $slug);
            }

            if (empty($validMovies)) {
                // Nếu không có phim hợp lệ trong batch này, chuyển sang batch tiếp theo
                continue;
            }

            // Chờ tất cả các promise hoàn thành
            try {
                $responses = [];
                foreach ($promises as $slug => $promise) {
                    try {
                        $responses[$slug] = $promise->wait();
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Lỗi khi gọi API cho phim ' . $slug . ': ' . $e->getMessage());
                        $errorCount++;
                    }
                }

                // Xử lý các response nhận được
                foreach ($responses as $slug => $response) {
                    try {
                        $resp = $response->json();
                        $movie = $validMovies[$slug];

                        if (!isset($resp['episodes']) || empty($resp['episodes'])) {
                            $errorCount++;
                            continue;
                        }

                        // Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
                        \Illuminate\Support\Facades\DB::beginTransaction();

                        try {
                            // Chọn server đầu tiên cho tất cả phim để đơn giản hóa
                            $firstServer = $resp['episodes'][0];
                            $serverName = $firstServer['server_name'];
                            $episodeCount = 0;

                            // Kiểm tra tập phim đã tồn tại chưa trước khi thêm mới
                            $existingEpisodes = Episode::where('movie_id', $movie->id)
                                ->where('server_name', $serverName)
                                ->pluck('episode')
                                ->toArray();

                            // Thêm tất cả tập của server đầu tiên
                            foreach ($firstServer['server_data'] as $res_data) {
                                // Bỏ qua tập đã tồn tại
                                if (in_array($res_data['name'], $existingEpisodes)) {
                                    continue;
                                }

                                $ep = new Episode();
                                $ep->movie_id = $movie->id;
                                $ep->linkphim = '<div class="hls-media-container" data-media-provider="hls"><video crossorigin="anonymous" preload="auto" playsinline class="hls-video video-js"><source src=" ' . $res_data['link_m3u8'] . '" type="application/x-mpegurl"></video></div>';
                                $ep->episode = $res_data['name'];
                                $ep->server_name = $serverName;
                                $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
                                $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
                                $ep->save();

                                // Tạo thông báo cho mỗi tập phim mới
                                \App\Http\Controllers\NotificationController::createNotification('new_episode', [
                                    'movie' => $movie,
                                    'episode' => $ep
                                ]);

                                $episodeCount++;
                            }

                            \Illuminate\Support\Facades\DB::commit();

                            if ($episodeCount > 0) {
                                $successCount++;
                                $episodesAdded += $episodeCount;

                                // Lưu tên phim để hiển thị thông báo (chỉ lưu 5 phim đầu)
                                if (count($movieNames) < 5) {
                                    $movieNames[] = $movie->title;
                                }

                                \Illuminate\Support\Facades\Log::info('Thêm thành công ' . $episodeCount . ' tập cho phim: ' . $movie->title);
                            }
                        } catch (\Exception $dbError) {
                            \Illuminate\Support\Facades\DB::rollBack();
                            \Illuminate\Support\Facades\Log::error('Lỗi DB khi thêm tập cho phim ' . $movie->title . ': ' . $dbError->getMessage());
                            $errorCount++;
                        }
                    } catch (\Exception $processError) {
                        \Illuminate\Support\Facades\Log::error('Lỗi khi xử lý dữ liệu phim ' . $slug . ': ' . $processError->getMessage());
                        $errorCount++;
                    }
                }

                // Giãn cách giữa các batch để giảm tải server API
                if ($batchIndex < count($batches) - 1) {
                    sleep(1);
                }
            } catch (\Exception $batchError) {
                \Illuminate\Support\Facades\Log::error('Lỗi khi xử lý batch: ' . $batchError->getMessage());
            }
        }

        // Tạo thông báo
        if ($successCount > 0) {
            $movieNamesText = implode(', ', $movieNames);
            if ($successCount > count($movieNames)) {
                $movieNamesText .= ' và ' . ($successCount - count($movieNames)) . ' phim khác';
            }

            return redirect()->route('episode.index')->with([
                'movie_title' => $movieNamesText,
                'success_message' => 'đã được thêm ' . $episodesAdded . ' tập phim. ',
                'action_type' => 'thêm tập API',
                'success_end' => 'thành công!'
            ]);
        } else {
            return redirect()->route('movie.index')->with('error', 'Không thể thêm tập cho phim nào. Vui lòng thử lại.');
        }
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

                // Tạo thông báo cho mỗi tập phim mới
                \App\Http\Controllers\NotificationController::createNotification('new_episode', [
                    'movie' => $movie,
                    'episode' => $ep
                ]);

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

            // Xác định loại phim (phimbo/phimle) dựa vào nhiều tiêu chí
            $isPhimBo = false;

            // 1. Kiểm tra trường time xem có chứa "/tập" không - đây là dấu hiệu rõ ràng nhất của phim bộ
            if (isset($res['time']) && !empty($res['time'])) {
                if (preg_match('/[\/\s]tập/i', $res['time'])) {
                    $isPhimBo = true;
                    \Illuminate\Support\Facades\Log::info('Xác định là phim bộ dựa vào time có chứa /tập: ' . $res['time']);
                } else {
                    \Illuminate\Support\Facades\Log::info('Thời lượng không chứa /tập: ' . $res['time'] . ' - có thể là phim lẻ');
                }
            }

            // 2. Kiểm tra episode_total nếu chưa xác định được từ time
            if (!$isPhimBo && isset($res['episode_total']) && !empty($res['episode_total'])) {
                // Nếu episode_total > 1 thì là phim bộ
                if (is_numeric($res['episode_total']) && (int)$res['episode_total'] > 1) {
                    $isPhimBo = true;
                    \Illuminate\Support\Facades\Log::info('Xác định là phim bộ dựa vào episode_total > 1: ' . $res['episode_total']);
                }
                // Trường hợp episode_total không phải số nhưng chứa các từ khóa đặc trưng của phim bộ
                // "Full" là phim lẻ, nên chỉ kiểm tra từ khóa "tập" và "hoàn"
                else if (preg_match('/tập|hoàn/i', $res['episode_total']) && !preg_match('/full/i', $res['episode_total'])) {
                    $isPhimBo = true;
                    \Illuminate\Support\Facades\Log::info('Xác định là phim bộ dựa vào episode_total chứa từ khóa đặc trưng: ' . $res['episode_total']);
                }
            }

            // 3. Kiểm tra episode_current nếu vẫn chưa xác định được
            if (!$isPhimBo && isset($res['episode_current'])) {
                // Nếu có "Full" và không kèm số tập (x/y) -> là phim lẻ
                if (preg_match('/full/i', $res['episode_current']) && !preg_match('/\(\d+\/\d+\)/', $res['episode_current'])) {
                    $isPhimBo = false;
                    \Illuminate\Support\Facades\Log::info('Xác định là phim lẻ dựa vào episode_current có "Full": ' . $res['episode_current']);
                }
                // Nếu có "Hoàn Tất(số/số)" hoặc có "Tập" -> là phim bộ
                else if (preg_match('/Hoàn Tất\s*\(\d+\/\d+\)|Tập/', $res['episode_current'])) {
                    $isPhimBo = true;
                    \Illuminate\Support\Facades\Log::info('Xác định là phim bộ dựa vào episode_current: ' . $res['episode_current']);
                }
            }

            // Áp dụng kết quả xác định
            $movie->thuocphim = $isPhimBo ? 'phimbo' : 'phimle';
            \Illuminate\Support\Facades\Log::info('Kết luận phim: ' . ($isPhimBo ? 'Phim bộ' : 'Phim lẻ'));

            // Lưu số tập cho phim bộ nếu là phim bộ
            if ($movie->thuocphim == 'phimbo') {
                if (isset($res['episode_total']) && !empty($res['episode_total'])) {
                    if (is_numeric($res['episode_total'])) {
                        $movie->sotap = $res['episode_total'];
                    } else {
                        // Cố gắng trích xuất số tập nếu chuỗi không phải số nguyên
                        if (preg_match('/(\d+)/', $res['episode_total'], $matches)) {
                            $movie->sotap = $matches[1];
                        } else {
                            $movie->sotap = 0; // Giá trị mặc định
                        }
                    }
                    \Illuminate\Support\Facades\Log::info('Số tập từ episode_total: ' . $movie->sotap);
                } elseif (isset($res['episode_current'])) {
                    // Thử trích xuất số tập từ các định dạng khác nhau
                    if (preg_match('/\((\d+)\/(\d+)\)/', $res['episode_current'], $matches)) {
                        $movie->sotap = $matches[2]; // Lấy tổng số tập từ định dạng (x/y)
                    } elseif (preg_match('/Hoàn Tất\s+\((\d+)\/(\d+)\)/', $res['episode_current'], $matches)) {
                        $movie->sotap = $matches[2];
                    } elseif (preg_match('/(\d+)/', $res['episode_current'], $matches)) {
                        $movie->sotap = $matches[1]; // Lấy số đầu tiên tìm thấy
                    }
                    \Illuminate\Support\Facades\Log::info('Số tập từ episode_current: ' . $movie->sotap);
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
            // Bước 1: Xác định danh mục phù hợp dựa vào type từ API
            $categoryFromName = null;

            // Map loại phim (type) từ API sang tên danh mục
            if (isset($res['type'])) {
                switch ($res['type']) {
                    case 'single':
                        $categoryFromName = 'Phim lẻ';
                        break;
                    case 'series':
                        $categoryFromName = 'Phim bộ';
                        break;
                    case 'tvshows':
                        $categoryFromName = 'Tv Shows';
                        break;
                    case 'hoathinh':
                        $categoryFromName = 'Phim hoạt hình';
                        break;
                    default:
                        $categoryFromName = $movie->thuocphim == 'phimbo' ? 'Phim bộ' : 'Phim lẻ';
                }
            } else {
                $categoryFromName = $movie->thuocphim == 'phimbo' ? 'Phim bộ' : 'Phim lẻ';
            }

            // Debug log
            \Illuminate\Support\Facades\Log::info('Đồng bộ danh mục phim: ' . $res['name']);
            \Illuminate\Support\Facades\Log::info('Type API: ' . ($res['type'] ?? 'không có'));
            \Illuminate\Support\Facades\Log::info('Tìm kiếm danh mục: ' . $categoryFromName);

            // Tìm danh mục phù hợp trong database - Cải thiện tìm kiếm chính xác hơn
            $existingCategory = Category::where('title', $categoryFromName)->first();

            // Nếu không tìm thấy chính xác, thử tìm gần đúng
            if (!$existingCategory) {
                $existingCategory = Category::where('title', 'like', $categoryFromName . '%')
                    ->orWhere('title', 'like', '%' . $categoryFromName . '%')
                    ->first();

                if ($existingCategory) {
                    \Illuminate\Support\Facades\Log::info('Tìm thấy danh mục gần đúng: ' . $existingCategory->title);
                }
            } else {
                \Illuminate\Support\Facades\Log::info('Tìm thấy danh mục chính xác: ' . $existingCategory->title);
            }

            // Thêm code kiểm tra xem danh mục có tồn tại không
            $allCategories = Category::select('id', 'title')->get();
            \Illuminate\Support\Facades\Log::info('Danh sách danh mục hiện có: ' . json_encode($allCategories->pluck('title')));

            if ($existingCategory) {
                $movie->category_id = $existingCategory->id;
                \Illuminate\Support\Facades\Log::info('Áp dụng danh mục: ' . $existingCategory->title . ' (ID: ' . $existingCategory->id . ')');
            } else {
                \Illuminate\Support\Facades\Log::info('Không tìm thấy danh mục phù hợp, sử dụng dự phòng');

                // TẠO DANH MỤC MỚI NẾU KHÔNG TỒN TẠI
                if ($res['type'] == 'tvshows' || $res['type'] == 'hoathinh') {
                    // Tạo danh mục mới nếu đây là type đặc biệt
                    $newCategory = new Category();
                    $newCategory->title = $categoryFromName;
                    $newCategory->description = 'Danh mục ' . $categoryFromName . ' được tạo tự động từ API';
                    $newCategory->status = 1;
                    $newCategory->slug = \Illuminate\Support\Str::slug($categoryFromName);
                    $newCategory->save();

                    $movie->category_id = $newCategory->id;
                    \Illuminate\Support\Facades\Log::info('Đã tạo và áp dụng danh mục mới: ' . $newCategory->title . ' (ID: ' . $newCategory->id . ')');
                } else {
                    // Nếu không phải type đặc biệt, sử dụng phân loại dự phòng
                    if ($movie->thuocphim == 'phimbo') {
                        $backupCategory = Category::where('title', 'like', '%phim bộ%')->first();
                    } else {
                        $backupCategory = Category::where('title', 'like', '%phim lẻ%')->first();
                    }

                    if ($backupCategory) {
                        $movie->category_id = $backupCategory->id;
                        \Illuminate\Support\Facades\Log::info('Áp dụng danh mục dự phòng: ' . $backupCategory->title . ' (ID: ' . $backupCategory->id . ')');
                    } else {
                        // Nếu không tìm thấy, lấy category đầu tiên
                        $category = Category::orderby('id', 'asc')->first();
                        $movie->category_id = $category->id;
                        \Illuminate\Support\Facades\Log::info('Áp dụng danh mục mặc định: ' . $category->title . ' (ID: ' . $category->id . ')');
                    }
                }
            }

            // Kiểm tra nếu phim có thể loại đặc biệt từ API (category array)
            if (isset($res['category']) && count($res['category']) > 0) {
                $importantCategories = [
                    'Phim Lẻ',
                    'Phim Bộ',
                    'Phim Chiếu Rạp',
                    'Phim Thuyết Minh',
                    'Phim Hoạt Hình',
                    'TV Shows'
                ];

                foreach ($res['category'] as $cat) {
                    if (in_array($cat['name'], $importantCategories)) {
                        // Map tên danh mục API sang tên danh mục database
                        $categoryMapping = [
                            'Phim Lẻ' => 'Phim lẻ',
                            'Phim Bộ' => 'Phim bộ',
                            'TV Shows' => 'Tv Shows',
                            'Phim Chiếu Rạp' => 'Phim chiếu rạp',
                            'Phim Hoạt Hình' => 'Phim hoạt hình',
                            'Phim Thuyết Minh' => 'Phim thuyết minh'
                        ];

                        $searchName = isset($categoryMapping[$cat['name']]) ? $categoryMapping[$cat['name']] : $cat['name'];

                        // Ưu tiên tìm danh mục theo tên đã được map
                        $foundCategory = Category::where('title', $searchName)
                            ->orWhere('title', 'like', $searchName . '%')
                            ->orWhere('title', 'like', '%' . $searchName . '%')
                            ->first();

                        if ($foundCategory) {
                            $movie->category_id = $foundCategory->id;
                            break; // Tìm thấy danh mục quan trọng thì dừng tìm kiếm
                        }
                    }
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

            // Tạo thông báo mới cho người dùng
            \App\Http\Controllers\NotificationController::createNotification('new_movie', [
                'movie' => $movie
            ]);

            return redirect()->back()->with([
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

        // Debug log
        \Illuminate\Support\Facades\Log::info('Bắt đầu thêm nhiều phim');
        \Illuminate\Support\Facades\Log::info('Dữ liệu nhận được: ' . $request->selected_movies);
        \Illuminate\Support\Facades\Log::info('Số phim đã chọn: ' . (is_array($selectedMovies) ? count($selectedMovies) : 0));

        if (empty($selectedMovies) || !is_array($selectedMovies)) {
            \Illuminate\Support\Facades\Log::error('Không nhận được dữ liệu phim hợp lệ');
            return redirect()->route('leech-movie')->with('error', 'Không có phim nào được chọn hoặc dữ liệu không hợp lệ');
        }

        // Đảm bảo mảng slugs không trống và không có các giá trị null
        $selectedMovies = array_filter($selectedMovies, function ($slug) {
            return !empty($slug) && is_string($slug);
        });

        // Kiểm tra lại sau khi lọc
        if (empty($selectedMovies)) {
            \Illuminate\Support\Facades\Log::error('Sau khi lọc, không còn slug nào hợp lệ');
            return redirect()->route('leech-movie')->with('error', 'Không có phim nào hợp lệ để thêm');
        }

        // Khởi tạo bộ đếm và mảng theo dõi
        $totalCount = count($selectedMovies);
        $processedCount = 0;
        $batchSize = 5; // Số lượng phim xử lý mỗi batch
        $successCount = 0;
        $failedCount = 0;
        $duplicateCount = 0;
        $successTitles = [];
        $processedSlugs = [];

        // Tăng thời gian thực thi tối đa lên 5 phút
        set_time_limit(300);

        // Chia nhỏ danh sách thành các batch để xử lý
        $batches = array_chunk($selectedMovies, $batchSize);
        \Illuminate\Support\Facades\Log::info('Chia thành ' . count($batches) . ' batch, mỗi batch ' . $batchSize . ' phim');

        // Xử lý từng batch
        foreach ($batches as $batchIndex => $batchSlugs) {
            \Illuminate\Support\Facades\Log::info('Đang xử lý batch ' . ($batchIndex + 1) . '/' . count($batches));

            // Chuẩn bị các promise để gọi API song song
            $promises = [];
            $validSlugs = [];

            // Kiểm tra phim đã tồn tại trước khi gọi API
            foreach ($batchSlugs as $slug) {
                if (empty($slug) || in_array($slug, $processedSlugs)) {
                    continue;
                }

                $processedSlugs[] = $slug;

                // Kiểm tra phim đã tồn tại chưa
                $existingMovie = Movie::where('slug', $slug)->first();
                if ($existingMovie) {
                    $duplicateCount++;
                    continue;
                }

                $validSlugs[] = $slug;
                $promises[$slug] = \Illuminate\Support\Facades\Http::async()->get('https://phimapi.com/phim/' . $slug);
            }

            if (empty($validSlugs)) {
                // Nếu không có slug hợp lệ trong batch này, chuyển sang batch tiếp theo
                continue;
            }

            // Chờ tất cả các promise hoàn thành
            try {
                $responses = [];
                foreach ($promises as $slug => $promise) {
                    try {
                        $responses[$slug] = $promise->wait();
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Lỗi khi gọi API cho slug ' . $slug . ': ' . $e->getMessage());
                        $failedCount++;
                    }
                }

                // Xử lý các response nhận được
                foreach ($responses as $slug => $response) {
                    try {
                        $resp = $response->json();

                        if (!isset($resp['movie'])) {
                            \Illuminate\Support\Facades\Log::error('Không tìm thấy thông tin phim từ API cho slug: ' . $slug);
                            $failedCount++;
                            continue;
                        }

                        // Thực hiện lưu phim
                        $movie = new Movie();
                        $res = $resp['movie'];

                        // Cài đặt các thuộc tính cơ bản
                        $movie->title = $res['name'];
                        $movie->trailer = $res['trailer_url'] ?? '';
                        $movie->name_eng = $res['origin_name'] ?? '';
                        $movie->slug = $res['slug'];
                        $movie->description = $res['content'] ?? '';
                        $movie->status = 1;
                        $movie->phim_hot = 1;
                        $movie->count_views = 0;
                        $movie->ngaytao = Carbon::now('Asia/Ho_Chi_Minh');
                        $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
                        $movie->image = $res['poster_url'] ?? '';
                        $movie->thumb_url = $res['thumb_url'] ?? '';
                        $movie->thoiluong = $res['time'] ?? '';

                        if (isset($res['year']) && !empty($res['year'])) {
                            $movie->year = $res['year'];
                        }

                        // Tạo tags
                        $tags = $res['name'];
                        if (isset($res['origin_name']) && !empty($res['origin_name'])) {
                            $tags .= ',' . $res['origin_name'];
                        }
                        $tags .= ',' . $res['slug'];

                        // Giới hạn danh sách diễn viên
                        if (isset($res['actor']) && is_array($res['actor'])) {
                            $limitedActors = array_slice($res['actor'], 0, 5);
                            $tags .= ',' . implode(',', $limitedActors);
                            $movie->actors = implode(', ', $res['actor']);
                        }

                        if (isset($res['director']) && is_array($res['director'])) {
                            $movie->director = implode(', ', $res['director']);
                        }

                        // Giới hạn độ dài tags
                        $movie->tags = substr($tags, 0, 255);

                        // Xác định phụ đề/thuyết minh
                        if (isset($res['lang'])) {
                            if (strpos($res['lang'], 'Thuyết Minh') !== false || strpos($res['lang'], 'Lồng Tiếng') !== false) {
                                $movie->phude = 1; // Thuyết minh
                            } else {
                                $movie->phude = 0; // Phụ đề
                            }
                        } else {
                            $movie->phude = 0;
                        }

                        // Xác định resolution
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

                        // Xác định loại phim (phimbo/phimle)
                        $isPhimBo = false;

                        // 1. Kiểm tra trường time
                        if (isset($res['time']) && !empty($res['time'])) {
                            if (preg_match('/[\/\s]tập/i', $res['time'])) {
                                $isPhimBo = true;
                            }
                        }

                        // 2. Kiểm tra episode_total
                        if (!$isPhimBo && isset($res['episode_total']) && !empty($res['episode_total'])) {
                            if (is_numeric($res['episode_total']) && (int)$res['episode_total'] > 1) {
                                $isPhimBo = true;
                            } else if (preg_match('/tập|hoàn/i', $res['episode_total']) && !preg_match('/full/i', $res['episode_total'])) {
                                $isPhimBo = true;
                            }
                        }

                        // 3. Kiểm tra episode_current
                        if (!$isPhimBo && isset($res['episode_current'])) {
                            if (preg_match('/full/i', $res['episode_current']) && !preg_match('/\(\d+\/\d+\)/', $res['episode_current'])) {
                                $isPhimBo = false;
                            } else if (preg_match('/Hoàn Tất\s*\(\d+\/\d+\)|Tập/', $res['episode_current'])) {
                                $isPhimBo = true;
                            }
                        }

                        // Áp dụng kết quả
                        $movie->thuocphim = $isPhimBo ? 'phimbo' : 'phimle';

                        // Lưu số tập cho phim bộ
                        if ($movie->thuocphim == 'phimbo') {
                            if (isset($res['episode_total']) && !empty($res['episode_total'])) {
                                if (is_numeric($res['episode_total'])) {
                                    $movie->sotap = $res['episode_total'];
                                } else {
                                    if (preg_match('/(\d+)/', $res['episode_total'], $matches)) {
                                        $movie->sotap = $matches[1];
                                    } else {
                                        $movie->sotap = 0;
                                    }
                                }
                            } elseif (isset($res['episode_current'])) {
                                if (preg_match('/\((\d+)\/(\d+)\)/', $res['episode_current'], $matches)) {
                                    $movie->sotap = $matches[2];
                                } elseif (preg_match('/Hoàn Tất\s+\((\d+)\/(\d+)\)/', $res['episode_current'], $matches)) {
                                    $movie->sotap = $matches[2];
                                } elseif (preg_match('/(\d+)/', $res['episode_current'], $matches)) {
                                    $movie->sotap = $matches[1];
                                }
                            }
                        }

                        // TÌM CATEGORY
                        $categoryFromName = null;

                        if (isset($res['type'])) {
                            switch ($res['type']) {
                                case 'single':
                                    $categoryFromName = 'Phim lẻ';
                                    break;
                                case 'series':
                                    $categoryFromName = 'Phim bộ';
                                    break;
                                case 'tvshows':
                                    $categoryFromName = 'Tv Shows';
                                    break;
                                case 'hoathinh':
                                    $categoryFromName = 'Phim hoạt hình';
                                    break;
                                default:
                                    $categoryFromName = $movie->thuocphim == 'phimbo' ? 'Phim bộ' : 'Phim lẻ';
                            }
                        } else {
                            $categoryFromName = $movie->thuocphim == 'phimbo' ? 'Phim bộ' : 'Phim lẻ';
                        }

                        // Tìm category trong database
                        $existingCategory = Category::where('title', $categoryFromName)
                            ->orWhere('title', 'like', '%' . $categoryFromName . '%')
                            ->first();

                        if ($existingCategory) {
                            $movie->category_id = $existingCategory->id;
                        } else {
                            // Sử dụng category dự phòng
                            if ($movie->thuocphim == 'phimbo') {
                                $backupCategory = Category::where('title', 'like', '%phim bộ%')->first();
                            } else {
                                $backupCategory = Category::where('title', 'like', '%phim lẻ%')->first();
                            }

                            if ($backupCategory) {
                                $movie->category_id = $backupCategory->id;
                            } else {
                                $movie->category_id = Category::orderBy('id', 'asc')->first()->id;
                            }
                        }

                        // XỬ LÝ COUNTRY
                        if (isset($res['country']) && count($res['country']) > 0) {
                            $foundCountry = false;
                            foreach ($res['country'] as $apiCountry) {
                                $countryName = trim($apiCountry['name']);
                                $existingCountry = Country::where('title', 'like', '%' . $countryName . '%')->first();
                                if ($existingCountry) {
                                    $movie->country_id = $existingCountry->id;
                                    $foundCountry = true;
                                    break;
                                }
                            }
                            if (!$foundCountry) {
                                $movie->country_id = Country::orderBy('id', 'asc')->first()->id;
                            }
                        } else {
                            $movie->country_id = Country::orderBy('id', 'asc')->first()->id;
                        }

                        // GENRE & MOVIE_GENRE
                        $genreIds = [];
                        if (isset($res['category']) && count($res['category']) > 0) {
                            foreach ($res['category'] as $apiGenre) {
                                $genreName = trim($apiGenre['name']);
                                $existingGenre = Genre::where('title', 'like', '%' . $genreName . '%')->first();
                                if ($existingGenre && !in_array($existingGenre->id, $genreIds)) {
                                    $genreIds[] = $existingGenre->id;
                                }
                            }
                        }

                        if (empty($genreIds)) {
                            $genreIds[] = Genre::orderBy('id', 'asc')->first()->id;
                        }

                        $movie->genre_id = $genreIds[0];

                        // Lưu phim
                        \Illuminate\Support\Facades\DB::beginTransaction();

                        try {
                            $movie->save();

                            // Sync genres
                            $movie->movie_genre()->sync($genreIds);

                            \Illuminate\Support\Facades\DB::commit();

                            // Tạo thông báo mới cho người dùng
                            \App\Http\Controllers\NotificationController::createNotification('new_movie', [
                                'movie' => $movie
                            ]);

                            $successCount++;

                            if (count($successTitles) < 5) {
                                $successTitles[] = mb_substr($res['name'], 0, 30, 'UTF-8') . (mb_strlen($res['name'], 'UTF-8') > 30 ? '...' : '');
                            }

                            \Illuminate\Support\Facades\Log::info('Lưu phim thành công: ' . $res['name']);
                        } catch (\Exception $saveError) {
                            \Illuminate\Support\Facades\DB::rollBack();
                            \Illuminate\Support\Facades\Log::error('Lỗi khi lưu phim ' . $res['name'] . ': ' . $saveError->getMessage());
                            $failedCount++;
                        }
                    } catch (\Exception $processError) {
                        \Illuminate\Support\Facades\Log::error('Lỗi khi xử lý phim ' . $slug . ': ' . $processError->getMessage());
                        $failedCount++;
                    }
                }

                // Tăng số lượng phim đã xử lý
                $processedCount += count($validSlugs);

                // Giãn cách giữa các batch để giảm tải server API
                if ($batchIndex < count($batches) - 1) {
                    sleep(1);
                }
            } catch (\Exception $batchError) {
                \Illuminate\Support\Facades\Log::error('Lỗi khi xử lý batch: ' . $batchError->getMessage());
            }
        }

        // Log kết quả
        \Illuminate\Support\Facades\Log::info('Kết quả thêm nhiều phim - Thành công: ' . $successCount . ', Thất bại: ' . $failedCount . ', Trùng lặp: ' . $duplicateCount);
        \Illuminate\Support\Facades\Log::info('Thêm nhiều phim hoàn thành.');

        // Tạo thông báo
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

        // Nếu không có từ khóa, hiển thị form tìm kiếm trống
        if (empty($keyword)) {
            // Lấy danh sách thể loại (genre) từ database cho dropdown
            $genres = \App\Models\Genre::where('status', 1)->orderBy('title')->get();
            // Lấy danh sách quốc gia từ database cho dropdown
            $countries = \App\Models\Country::where('status', 1)->orderBy('title')->get();

            // Các giá trị mặc định
            $resp = ['data' => ['items' => [], 'params' => ['pagination' => ['totalPages' => 0, 'totalItems' => 0]]]];
            $page = 1;
            $totalPages = 0;
            $totalItems = 0;
            $sort_field = 'modified.time';
            $sort_type = 'desc';
            $sort_lang = '';
            $category = '';
            $country = '';
            $year = '';
            $type = '';
            $limit = '150';

            return view('admincp.leech.search', compact(
                'resp',
                'genres',
                'countries',
                'keyword',
                'page',
                'totalPages',
                'totalItems',
                'sort_field',
                'sort_type',
                'sort_lang',
                'category',
                'country',
                'year',
                'type',
                'limit'
            ));
        }

        $page = $request->input('page', 1);

        // Các tham số lọc (nếu có)
        $sort_field = $request->input('sort_field', 'modified.time');
        $sort_type = $request->input('sort_type', 'desc');
        $sort_lang = $request->input('sort_lang', '');
        $category = $request->input('category', '');
        $country = $request->input('country', '');
        $year = $request->input('year', '');
        $type = $request->input('type', '');
        $limit = $request->input('limit', '150');

        // Xây dựng URL API tìm kiếm
        $apiUrl = "https://phimapi.com/v1/api/tim-kiem?keyword={$keyword}&page={$page}";

        // Log URL ban đầu
        \Illuminate\Support\Facades\Log::info('API URL ban đầu: ' . $apiUrl);

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
        if (!empty($type)) {
            $apiUrl .= "&type={$type}";
        }
        if (!empty($limit)) {
            $apiUrl .= "&limit={$limit}";
        }

        // Log URL cuối cùng 
        \Illuminate\Support\Facades\Log::info('Final API URL: ' . $apiUrl);

        // Debug tham số
        \Illuminate\Support\Facades\Log::info('API Parameters: sort_field=' . $sort_field . ', sort_type=' . $sort_type .
            ', category=' . $category . ', country=' . $country . ', year=' . $year . ', type=' . $type);

        // Gọi API và lấy kết quả
        $resp = Http::get($apiUrl)->json();

        // Lấy thông tin tổng số trang từ API (nếu có)
        $totalPages = $resp['data']['params']['pagination']['totalPages'] ?? 1;
        $totalItems = $resp['data']['params']['pagination']['totalItems'] ?? 0;

        // Lấy danh sách thể loại (genre) từ database
        $genres = \App\Models\Genre::where('status', 1)->orderBy('title')->get();

        // Lấy danh sách quốc gia từ database
        $countries = \App\Models\Country::where('status', 1)->orderBy('title')->get();

        // Truyền dữ liệu cho view
        return view('admincp.leech.search', compact(
            'resp',
            'page',
            'totalPages',
            'keyword',
            'totalItems',
            'sort_field',
            'sort_type',
            'sort_lang',
            'category',
            'country',
            'year',
            'type',
            'limit',
            'genres',
            'countries'
        ));
    }

    /**
     * Hiển thị danh sách tổng hợp phim từ API
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function api_list(Request $request)
    {
        // Lấy các tham số từ request
        $type_list = $request->input('type_list', 'phim-bo'); // Mặc định là phim-bo
        $page = $request->input('page', 1);
        $sort_field = $request->input('sort_field', 'modified.time');
        $sort_type = $request->input('sort_type', 'desc');
        $sort_lang = $request->input('sort_lang', '');
        $category = $request->input('category', '');
        $country = $request->input('country', '');
        $year = $request->input('year', '');
        $limit = $request->input('limit', 36);

        // Xây dựng URL API
        $apiUrl = "https://phimapi.com/v1/api/danh-sach/{$type_list}?page={$page}";

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

        // Log URL API
        \Illuminate\Support\Facades\Log::info('API URL: ' . $apiUrl);

        // Gọi API và lấy kết quả
        $resp = Http::get($apiUrl)->json();

        // Debug: Kiểm tra cấu trúc dữ liệu trả về
        if (isset($resp['data']['items']) && count($resp['data']['items']) > 0) {
            $firstItem = $resp['data']['items'][0];
            \Illuminate\Support\Facades\Log::info('Mẫu item đầu tiên: ' . json_encode($firstItem));
            \Illuminate\Support\Facades\Log::info('poster_url: ' . ($firstItem['poster_url'] ?? 'không có'));
        } else {
            \Illuminate\Support\Facades\Log::warning('Không có dữ liệu items trả về từ API');
        }

        // Lấy thông tin tổng số trang từ API
        $totalPages = $resp['data']['params']['pagination']['totalPages'] ?? 1;
        $totalItems = $resp['data']['params']['pagination']['totalItems'] ?? 0;

        // Danh sách các loại danh sách có thể sử dụng
        $listTypes = [
            'phim-bo' => 'Phim Bộ',
            'phim-le' => 'Phim Lẻ',
            'tv-shows' => 'TV Shows',
            'hoat-hinh' => 'Hoạt Hình',
            'phim-vietsub' => 'Phim Vietsub',
            'phim-thuyet-minh' => 'Phim Thuyết Minh',
            'phim-long-tieng' => 'Phim Lồng Tiếng'
        ];

        // Lấy danh sách thể loại (genre) từ database
        $genres = \App\Models\Genre::where('status', 1)->orderBy('title')->get();

        // Lấy danh sách quốc gia từ database
        $countries = \App\Models\Country::where('status', 1)->orderBy('title')->get();

        // Truyền dữ liệu cho view
        return view('admincp.leech.api_list', compact(
            'resp',
            'page',
            'totalPages',
            'totalItems',
            'type_list',
            'listTypes',
            'sort_field',
            'sort_type',
            'sort_lang',
            'category',
            'country',
            'year',
            'limit',
            'genres',
            'countries'
        ));
    }
}