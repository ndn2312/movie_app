<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Episode;
use Carbon\Carbon;

class EpisodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $query = Episode::with('movie')->orderBy('movie_id', 'DESC');

        if ($search) {
            $query->whereHas('movie', function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%');
            })->orWhere('episode', 'LIKE', '%' . $search . '%');
        }

        $list_episode = $query->paginate(6);

        if ($search) {
            $list_episode->appends(['search' => $search]);
        }

        // Nếu là request AJAX, chỉ trả về phần tử HTML cần thiết
        if ($request->ajax || $request->ajax === 'true') {
            return view('admincp.episode.index', compact('list_episode'));
        }

        return view('admincp.episode.index', compact('list_episode'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list_movie = Movie::orderBy('id', 'DESC')->pluck('title', 'id');
        return view('admincp.episode.form', compact('list_movie'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $episode_check = Episode::where('episode', $data['episode'])
            ->where('movie_id', $data['movie_id'])
            ->count();

        if ($episode_check > 0) {
            return redirect()->back()->with([
                'movie_title' => $data['episode'],
                'error_message' => 'đã tồn tại',
                'action_type' => 'trùng lặp',
                'error_end' => 'không thể thêm! ',
                'error_duplicate' => true
            ]);
        } else {
            $ep = new Episode();
            $ep->movie_id = $data['movie_id'];
            $ep->episode = $data['episode'];

            // Xử lý upload video nếu có
            if ($request->hasFile('video_file')) {
                $file = $request->file('video_file');
                $filename = $data['movie_id'] . '_ep' . $data['episode'] . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = 'uploads/videos/';

                // Tạo thư mục nếu chưa tồn tại
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0755, true);
                }

                // Lưu file
                $file->move(public_path($path), $filename);

                // Tạo đường dẫn đầy đủ cho video
                $videoPath = asset($path . $filename);

                // Tạo HTML video player hoặc chỉ lưu đường dẫn
                $ep->linkphim = '<video controls src="' . $videoPath . '" class="video-js"></video>';
                // Hoặc chỉ lưu đường dẫn: $ep->linkphim = $videoPath;
            } else if (!empty($data['link'])) {
                $ep->linkphim = $this->detectAndCreateIframe($data['link']);
            } else {
                // Trường hợp không có cả file và link, gán giá trị mặc định
                $ep->linkphim = 'Chưa có link phim';
                // Hoặc redirect về với thông báo lỗi
                // return redirect()->back()->with('error', 'Vui lòng cung cấp link phim hoặc tải lên file video');
            }

            $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
            $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
            $ep->save();

            // Lấy thông tin phim để thêm vào thông báo
            $movie = Movie::find($data['movie_id']);

            // Tạo thông báo mới cho người dùng
            \App\Http\Controllers\NotificationController::createNotification('new_episode', [
                'movie' => $movie,
                'episode' => $ep
            ]);

            // Mã trả về giữ nguyên
            $referer = request()->headers->get('referer');
            $notification = [
                'movie_title' => $data['episode'],
                'success_message' => 'đã được',
                'action_type' => 'thêm',
                'success_end' => 'thành công! ',
            ];

            if (strpos($referer, 'add_episode') !== false) {
                return redirect()->back()->with($notification);
            } else {
                return redirect()->to('episode')->with($notification);
            }
        }
    }

    // Thêm hàm detectAndCreateIframe để xử lý link
    private function detectAndCreateIframe($link)
    {
        $link = trim($link);

        // Nếu là mã iframe đầy đủ, giữ nguyên
        if (strpos($link, '<iframe') === 0) {
            return $link;
        }

        // Nếu là link m3u8, sử dụng HLS player
        if (strpos($link, '.m3u8') !== false) {
            return '<div class="hls-media-container" data-media-provider="hls"><video crossorigin="anonymous" preload="auto" playsinline class="hls-video video-js"><source src="' . $link . '" type="application/x-mpegurl"></video></div>';
        }

        // Xử lý link embed từ các platform phổ biến

        // YouTube
        if (
            preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $link, $matches) ||
            preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $link, $matches) ||
            preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $link, $matches)
        ) {
            $videoId = $matches[1];
            return '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/([0-9]+)/', $link, $matches)) {
            $videoId = $matches[1];
            return '<iframe src="https://player.vimeo.com/video/' . $videoId . '" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
        }

        // Facebook
        if (strpos($link, 'facebook.com/') !== false && strpos($link, 'video') !== false) {
            return '<iframe src="https://www.facebook.com/plugins/video.php?href=' . urlencode($link) . '&show_text=0" width="100%" height="100%" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>';
        }

        // Google Drive
        if (strpos($link, 'drive.google.com') !== false) {
            $link = str_replace('/view', '/preview', $link);
            return '<iframe src="' . $link . '" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>';
        }

        // Nếu link chứa HTTP/HTTPS và có vẻ như là embed link (chứa từ embed/player)
        if ((strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0) &&
            (strpos($link, 'embed') !== false || strpos($link, 'player') !== false)
        ) {
            return '<iframe src="' . $link . '" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>';
        }

        // Nếu là URL trực tiếp đến file video
        $videoExtensions = ['.mp4', '.webm', '.ogg', '.mov', '.avi', '.wmv', '.flv', '.mkv'];
        foreach ($videoExtensions as $ext) {
            if (strpos(strtolower($link), $ext) !== false) {
                return '<video controls src="' . $link . '" class="video-js"></video>';
            }
        }

        // Cho các trường hợp còn lại, giả định là link cần nhúng trong iframe
        if (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0) {
            return '<iframe src="' . $link . '" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>';
        }

        // Trường hợp không xác định, trả về nguyên link
        return $link;
    }

    public function add_episode($id)
    {
        $movie = Movie::find($id);
        $list_episode = Episode::with('movie')->where('movie_id', $id)->orderBy('episode', 'DESC')->get();
        return view('admincp.episode.add_episode', compact('list_episode', 'movie',));
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
        $list_movie = Movie::orderBy('id', 'DESC')->pluck('title', 'id');
        $episode = Episode::find($id);
        return view('admincp.episode.form', compact('episode', 'list_movie'));
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
        $data = $request->all();
        $ep = Episode::find($id);
        $ep->movie_id = $data['movie_id'];

        // Xử lý upload video nếu có
        if ($request->hasFile('video_file')) {
            // Nếu đã có video cũ, xóa file cũ
            if (strpos($ep->linkphim, '<video') !== false) {
                preg_match('/src="([^"]+)"/', $ep->linkphim, $matches);
                if (isset($matches[1])) {
                    $oldPath = public_path(str_replace(asset(''), '', $matches[1]));
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }

            $file = $request->file('video_file');
            $filename = $data['movie_id'] . '_ep' . $data['episode'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/videos/';

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }

            // Lưu file
            $file->move(public_path($path), $filename);
            $videoPath = asset($path . $filename);

            // Tạo HTML cho video player
            $ep->linkphim = '<video controls src="' . $videoPath . '" class="video-js"></video>';
        } else if (!empty($data['link'])) {
            // Nếu có video cũ và người dùng thay đổi sang link khác, xóa file cũ
            if (strpos($ep->linkphim, '<video') !== false) {
                preg_match('/src="([^"]+)"/', $ep->linkphim, $matches);
                if (isset($matches[1])) {
                    $oldPath = public_path(str_replace(asset(''), '', $matches[1]));
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            }

            $ep->linkphim = $this->detectAndCreateIframe($data['link']);
        }
        // Nếu không upload file mới và không nhập link mới, giữ nguyên link cũ

        $ep->episode = $data['episode'];
        $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $ep->save();

        return redirect()->to('episode')->with([
            'success_message' => 'đã được',
            'action_type' => 'cập nhật',
            'success_end' => 'thành công! ',
            'movie_title' => $data['episode']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // Thêm chức năng xóa file khi xóa episode
    public function destroy($id)
    {
        $ep = Episode::find($id);

        // Nếu là video tự upload
        if (strpos($ep->linkphim, '<video') !== false) {
            // Trích xuất đường dẫn file từ thẻ video
            preg_match('/src="([^"]+)"/', $ep->linkphim, $matches);
            if (isset($matches[1])) {
                $filePath = public_path(str_replace(asset(''), '', $matches[1]));
                if (file_exists($filePath)) {
                    unlink($filePath); // Xóa file
                }
            }
        }

        // Xóa thông báo liên quan đến tập phim
        \App\Models\Notification::where('episode_id', $ep->id)->delete();

        $ep->delete();
        return redirect()->back()->with([
            'movie_title' => $ep->episode,
            'delete_message' => 'đã được',
            'action_type' => 'xóa',
            'delete_end' => 'thành công! ',
        ]);
    }

    /**
     * Xóa nhiều tập phim cùng lúc
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request)
    {
        // Lấy danh sách ID các tập phim đã chọn
        $episodeIds = json_decode($request->input('episode_ids'), true);

        if (empty($episodeIds) || !is_array($episodeIds)) {
            return redirect()->back()->with('error', 'Không có tập phim nào được chọn để xóa');
        }

        $count = 0;
        $movieTitles = [];
        $maxMoviesToShow = 3; // Số lượng tên phim hiển thị trong thông báo

        foreach ($episodeIds as $id) {
            $episode = Episode::find($id);

            if ($episode) {
                $movieTitle = $episode->movie->title . ' - ' . $episode->episode;

                if (count($movieTitles) < $maxMoviesToShow) {
                    $movieTitles[] = $movieTitle;
                }

                // Nếu là video tự upload, xóa file
                if (strpos($episode->linkphim, '<video') !== false) {
                    // Trích xuất đường dẫn file từ thẻ video
                    preg_match('/src="([^"]+)"/', $episode->linkphim, $matches);
                    if (isset($matches[1])) {
                        $filePath = public_path(str_replace(asset(''), '', $matches[1]));
                        if (file_exists($filePath)) {
                            unlink($filePath); // Xóa file
                        }
                    }
                }

                // Xóa thông báo liên quan đến tập phim này
                \App\Models\Notification::where('episode_id', $episode->id)->delete();

                // Xóa tập phim
                $episode->delete();
                $count++;
            }
        }

        if ($count > 0) {
            $titleDisplay = implode(', ', $movieTitles);

            if ($count > $maxMoviesToShow) {
                $titleDisplay .= ' và ' . ($count - $maxMoviesToShow) . ' tập phim khác';
            }

            return redirect()->back()->with([
                'movie_title' => $titleDisplay,
                'delete_message' => 'đã được',
                'action_type' => 'xóa',
                'delete_end' => 'thành công!',
            ]);
        } else {
            return redirect()->back()->with('error', 'Không thể xóa các tập phim đã chọn');
        }
    }

    public function select_movie()
    {
        $id = $_GET['id'];
        $movie = Movie::find($id);
        $output = '<option value="">---Chọn tập phim---</option>';
        if ($movie->thuocphim == 'phimbo') {
            for ($i = 1; $i <= $movie->sotap; $i++) {
                $output .= '<option value="' . $i . '">Tập ' . $i . '</option>';
            };
        } else {
            $output .= '<option value="HD">HD</option>
            <option value="FullHD">FullHD</option>
            <option value="Cam">Cam</option>';
        }

        echo $output;
    }

    /**
     * Lấy danh sách ID tất cả các tập phim trong hệ thống
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllIds()
    {
        // Lấy tất cả ID của tập phim
        $episodeIds = Episode::pluck('id')->toArray();

        return response()->json([
            'success' => true,
            'episode_ids' => $episodeIds,
            'count' => count($episodeIds)
        ]);
    }
}
