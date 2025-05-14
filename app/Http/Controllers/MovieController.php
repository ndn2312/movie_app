<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;

use App\Models\Genre;

use App\Models\Country;
use Carbon\Carbon;
use App\Models\Movie_Genre;
use Illuminate\Support\Facades\File;
use App\Models\Episode;
use App\Models\Rating;

use PHPUnit\Framework\Constraint\Count;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Format view count to K, M format
     * 
     * @param int $count
     * @return string
     */
    private function formatViewCount($count)
    {
        if ($count >= 1000000) {
            return number_format($count / 1000000, 1) . 'M';
        } elseif ($count >= 1000) {
            return number_format($count / 1000, 1) . 'K';
        } else {
            return number_format($count);
        }
    }

    public function category_choose(Request $request)
    {
        $data = $request->all();
        $movie = movie::find($data['movie_id']);
        $movie->category_id = $data['category_id'];
        $movie->save();
    }
    public function country_choose(Request $request)
    {

        $data = $request->all();
        $movie = movie::find($data['movie_id']);
        $movie->country_id = $data['country_id'];
        $movie->save();
    }
    public function phimhot_choose(Request $request)
    {
        $data = $request->all();
        $movie = movie::find($data['movie_id']);
        $movie->phim_hot = $data['phimhot_val'];
        $movie->save();
    }
    public function phude_choose(Request $request)
    {
        $data = $request->all();
        $movie = movie::find($data['movie_id']);
        $movie->phude = $data['phude_val'];
        $movie->save();
    }
    public function status_choose(Request $request)
    {
        $data = $request->all();
        $movie = movie::find($data['movie_id']);
        $movie->status = $data['status_val'];
        $movie->save();
    }
    public function resolution_choose(Request $request)
    {
        $data = $request->all();
        $movie = movie::find($data['movie_id']);
        $movie->resolution = $data['resolution_val'];
        $movie->save();
    }
    public function thuocphim_choose(Request $request)
    {
        $data = $request->all();
        $movie = movie::find($data['movie_id']);
        $movie->thuocphim = $data['thuocphim_val'];
        $movie->save();
    }
    // Xử lý hiện thị ảnh đại diện
    public function update_image_movie_ajax(Request $request)
    {
        $get_image = $request->file('file');
        $movie_id = $request->movie_id;

        if ($get_image) {
            $movie = Movie::find($movie_id);

            // Kiểm tra trước khi xóa file cũ
            if (!empty($movie->image) && file_exists('uploads/movie/' . $movie->image)) {
                unlink('uploads/movie/' . $movie->image);
            }

            // Xử lý tên file an toàn hơn
            $extension = $get_image->getClientOriginalExtension();
            $new_image = time() . '_' . rand(0, 999) . '.' . $extension;

            // Đảm bảo thư mục tồn tại
            $path = 'uploads/movie';
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $get_image->move($path, $new_image);
            $movie->image = $new_image;
            $movie->save();
        }
    }


    public function index()
    {
        // Lấy danh sách phim với các thông tin quan hệ
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

        $category = Category::pluck('title', 'id');
        $country = Country::pluck('title', 'id');

        $path = public_path("/json/");
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        File::put($path . 'movies.json', json_encode($list));

        return view('admincp.movie.index', compact('list', 'category', 'country'));
    }


    public function update_year(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);

        $movie->year = $data['year'];
        $movie->save();
    }
    public function update_season(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);

        $movie->season = $data['season'];
        $movie->save();

        return response()->json(['success' => true, 'message' => 'Đã cập nhật season thành công']);
    }
    public function update_topview(Request $request)
    {
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);

        $movie->topview = $data['topview'];
        $movie->save();
    }

    public function filter_topview(Request $request)
    {

        $data = $request->all();
        $movie = Movie::where('topview', $data['value'])
            ->orderBy('ngaycapnhat', 'DESC')
            ->take(5)
            ->get();

        $output = '';

        foreach ($movie as $key => $mov) {
            // Xác định phân giải hiển thị
            if ($mov->resolution == 0) {
                $text = 'HD';
            } elseif ($mov->resolution == 1) {
                $text = 'SD';
            } elseif ($mov->resolution == 2) {
                $text = 'HDCam';
            } elseif ($mov->resolution == 3) {
                $text = 'Cam';
            } elseif ($mov->resolution == 4) {
                $text = 'FullHD';
            } else {
                $text = 'Trailer';
            }

            // Kiểm tra nếu ảnh là URL hay file local
            $image_path = substr($mov->image, 0, 5) === 'https' ? $mov->image : url('uploads/movie/' . $mov->image);

            // Tạo HTML cho mỗi phim
            $output .= '<div class="item">
                    <a href="' . url('phim/' . $mov->slug) . '" title="' . $mov->title . '">
                       <div class="item-link">
                          <img src="' . $image_path . '" class="lazy post-thumb" alt="' . $mov->title . '" title="' . $mov->title . '"/>
                          <span class="is_trailer">' . $text . '</span>
                       </div>
                       <p class="title">' . $mov->title . '</p>
                    </a>
                    <div class="viewsCount" style="color: #9d9d9d;">
                            ' . $this->formatViewCount($mov->count_views) . ' lượt xem

                    </div>
                    <div style="float: left;">
                       <span class="user-rate-image post-large-rate stars-large-vang" style="display: block;/* width: 100%; */">
                       <span style="width: 0%"></span>
                       </span>
                    </div>
                </div>';
        }

        echo $output;
    }

    public function filter_default(Request $request)
    {

        $data = $request->all();
        $movie = Movie::where('topview', 0)
            ->orderBy('ngaycapnhat', 'DESC')
            ->take(5)
            ->get();

        $output = '';

        foreach ($movie as $key => $mov) {
            // Xác định phân giải hiển thị
            if ($mov->resolution == 0) {
                $text = 'HD';
            } elseif ($mov->resolution == 1) {
                $text = 'SD';
            } elseif ($mov->resolution == 2) {
                $text = 'HDCam';
            } elseif ($mov->resolution == 3) {
                $text = 'Cam';
            } elseif ($mov->resolution == 4) {
                $text = 'FullHD';
            } else {
                $text = 'Trailer';
            }

            // Kiểm tra nếu ảnh là URL hay file local
            $image_path = substr($mov->image, 0, 5) === 'https' ? $mov->image : url('uploads/movie/' . $mov->image);

            // Tạo HTML cho mỗi phim
            $output .= '<div class="item">
                    <a href="' . url('phim/' . $mov->slug) . '" title="' . $mov->title . '">
                       <div class="item-link">
                          <img src="' . $image_path . '" class="lazy post-thumb" alt="' . $mov->title . '" title="' . $mov->title . '"/>
                          <span class="is_trailer">' . $text . '</span>
                       </div>
                       <p class="title">' . $mov->title . '</p>
                    </a>
                    <div class="viewsCount" style="color: #9d9d9d;">
                            ' . $this->formatViewCount($mov->count_views) . ' lượt xem

                    </div>
                    <div style="float: left;">
                       <span class="user-rate-image post-large-rate stars-large-vang" style="display: block;/* width: 100%; */">
                       <span style="width: 0%"></span>
                       </span>
                    </div>
                </div>';
        }

        echo $output;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::pluck('title', 'id');
        $genre = Genre::pluck('title', 'id');
        $list_genre = Genre::all();
        $country = Country::pluck('title', 'id');

        return view('admincp.movie.form', compact('genre', 'country', 'category', 'list_genre'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'required|unique:movies|max:255',
                'trailer' => 'nullable',
                'sotap' => 'required',
                'tags' => 'nullable',
                'thoiluong' => 'required',
                'resolution' => 'required',
                'phude' => 'required',
                'name_eng' => 'required',
                'phim_hot' => 'required|boolean',
                'slug' => 'required|unique:movies|max:255',
                'description' => 'required',
                'status' => 'required|boolean',
                'category_id' => 'required|exists:categories,id',
                'thuocphim' => 'required',
                'country_id' => 'required|exists:countries,id',
                'genre' => 'required|array',

                // Thêm các quy tắc xác thực khác nếu cần
            ],
            [
                // Thêm thông báo lỗi nếu cần
                'title.required' => 'Tên phim không được để trống',
                'title.unique' => 'Tên phim đã tồn tại',
                'trailer.required' => 'Trailer không được để trống',
                'sotap.required' => 'Số tập không được để trống',
                'tags.required' => 'Tags không được để trống',
                'thoiluong.required' => 'Thời lượng không được để trống',
                'resolution.required' => 'Phân giải không được để trống',
                'phude.required' => 'Phụ đề không được để trống',
                'name_eng.required' => 'Tên tiếng anh không được để trống',
                'slug.required' => 'Slug không được để trống',
                'slug.unique' => 'Slug đã tồn tại',
                'description.required' => 'Mô tả không được để trống',
                'status.required' => 'Trạng thái không được để trống',
                'status.boolean' => 'Trạng thái không hợp lệ',
                'category_id.required' => 'Danh mục không được để trống',
                'category_id.exists' => 'Danh mục không tồn tại',
            ]
        );
        $movie = new Movie();
        $movie->title = $data['title'];
        $movie->trailer = $data['trailer'];
        $movie->sotap = $data['sotap'];

        $movie->tags = $data['tags'];

        $movie->thoiluong = $data['thoiluong'];

        $movie->resolution = $data['resolution'];
        $movie->phude = $data['phude'];
        $movie->name_eng = $data['name_eng'];
        $movie->phim_hot = $data['phim_hot'];
        $movie->slug = $data['slug'];
        $movie->description = $data['description'];
        $movie->status = $data['status'];
        $movie->category_id = $data['category_id'];
        $movie->thuocphim = $data['thuocphim'];
        $movie->country_id = $data['country_id'];
        $movie->count_views = 0;

        // Lưu thông tin đạo diễn và diễn viên nếu có
        if (isset($data['director'])) {
            $movie->director = $data['director'];
        }

        if (isset($data['actors'])) {
            $movie->actors = $data['actors'];
        }

        // Lưu URL poster và thumbnail nếu có
        if (isset($data['poster_url']) && !empty($data['poster_url'])) {
            $movie->poster_url = $data['poster_url'];
        }

        if (isset($data['thumb_url']) && !empty($data['thumb_url'])) {
            $movie->thumb_url = $data['thumb_url'];
        }

        $movie->ngaytao = Carbon::now('Asia/Ho_Chi_Minh');
        $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
        foreach ($data['genre'] as $key => $gen) {
            $movie->genre_id = $gen[0];
        }
        // $movie->genre_id = $data['genre_id'];


        // Xử lý upload hình ảnh
        $get_image = $request->file('image');
        if ($get_image) {
            $path = public_path('uploads/movie/');

            // Lấy tên gốc và phần mở rộng
            $original_name = $get_image->getClientOriginalName();
            $extension = $get_image->getClientOriginalExtension();
            $filename = pathinfo($original_name, PATHINFO_FILENAME);

            // Tạo tên file mới tránh trùng lặp
            $new_image = $filename . '_' . Str::random(10) . '.' . $extension;

            // Di chuyển file vào thư mục
            $get_image->move($path, $new_image);

            // Lưu vào database
            $movie->image = $new_image;
        } elseif (isset($data['poster_url']) && !empty($data['poster_url'])) {
            // Nếu không upload ảnh nhưng có poster_url thì dùng poster_url làm ảnh chính
            $movie->image = $data['poster_url'];
        }

        $movie->save();
        // them nhieu the loai cho phim
        $movie->movie_genre()->attach($data['genre']);

        // Truyền thông tin để hiển thị thông báo
        return redirect()->route('movie.index')->with([
            'success_message' => 'đã được',
            'action_type' => 'thêm',
            'success_end' => 'thành công!',
            'movie_title' => $data['title']
        ]);
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
        $category = Category::pluck('title', 'id');
        $genre = Genre::pluck('title', 'id');
        $country = Country::pluck('title', 'id');
        $list_genre = Genre::all();

        $movie = Movie::find($id);
        $movie_genre = $movie->movie_genre;
        return view('admincp.movie.form', compact('genre', 'country', 'category', 'movie', 'list_genre', 'movie_genre'));
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
        try {
            $data = $request->all();

            $movie = Movie::find($id);
            $movie->title = $data['title'];
            $movie->trailer = $data['trailer'];
            $movie->sotap = $data['sotap'];

            $movie->tags = $data['tags'];

            $movie->thoiluong = $data['thoiluong'];
            $movie->resolution = $data['resolution'];
            $movie->phim_hot = $data['phim_hot'];
            $movie->phude = $data['phude'];
            $movie->slug = $data['slug'];
            $movie->description = $data['description'];
            $movie->status = $data['status'];
            $movie->category_id = $data['category_id'];
            $movie->thuocphim = $data['thuocphim'];
            // $movie->count_views = rand(100, 99999);

            // $movie->genre_id = $data['genre_id'];
            $movie->country_id = $data['country_id'];

            // Cập nhật thông tin đạo diễn và diễn viên nếu có
            if (isset($data['director'])) {
                $movie->director = $data['director'];
            }

            if (isset($data['actors'])) {
                $movie->actors = $data['actors'];
            }

            // Lưu URL poster và thumbnail nếu có
            if (isset($data['poster_url']) && !empty($data['poster_url'])) {
                $movie->poster_url = $data['poster_url'];
            }

            if (isset($data['thumb_url']) && !empty($data['thumb_url'])) {
                $movie->thumb_url = $data['thumb_url'];
            }

            $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
            foreach ($data['genre'] as $key => $gen) {
                $movie->genre_id = $gen[0];
            }

            $movie->save();
            $movie->movie_genre()->sync($data['genre']);


            $get_image = $request->file('image');

            if ($get_image) {
                if (!empty($movie->image) && substr($movie->image, 0, 5) != 'https' && file_exists('uploads/movie/' . $movie->image)) {
                    unlink('uploads/movie/' . $movie->image);
                }


                $path = public_path('uploads/movie/');

                // Lấy tên gốc và phần mở rộng
                $original_name = $get_image->getClientOriginalName();
                $extension = $get_image->getClientOriginalExtension();
                $filename = pathinfo($original_name, PATHINFO_FILENAME);

                // Tạo tên file mới tránh trùng lặp
                $new_image = $filename . '_' . Str::random(10) . '.' . $extension;

                // Di chuyển file vào thư mục
                $get_image->move($path, $new_image);

                // Lưu vào database (giả sử có $movie)
                $movie->image = $new_image;

                $movie->save();
            } elseif (isset($data['poster_url']) && !empty($data['poster_url']) && (empty($movie->image) || substr($movie->image, 0, 5) == 'https')) {
                // Nếu không upload ảnh mới nhưng có poster_url và không có ảnh cũ hoặc ảnh cũ là URL
                $movie->image = $data['poster_url'];
            }

            return redirect()->route('movie.index')->with([
                'success_message' => 'đã được',
                'action_type' => 'cập nhật',
                'success_end' => 'thành công!',
                'movie_title' => $data['title']
            ]);
        } catch (\Exception $e) {
            // Trả về thông báo lỗi nếu có vấn đề
            return redirect()->route('movie.index')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Tìm movie trong database
            $movie = Movie::findOrFail($id);
            $movie_title = $movie->title;

            // Kiểm tra và xóa hình ảnh nếu tồn tại
            if (!empty($movie->image)) {
                // Chỉ xóa file từ server nếu không phải là URL
                if (substr($movie->image, 0, 5) != 'https') {
                    $image_path = public_path('uploads/movie/' . $movie->image);
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }
            }
            // xóa nhieu the loai cho phim

            Movie_Genre::whereIn('movie_id', [$movie->id])->delete();
            // Xóa tập phim
            Episode::whereIn('movie_id', [$movie->id])->delete();
            // Xóa movie trong database
            $movie->delete();

            // Truyền thông tin để hiển thị thông báo
            return redirect()->back()->with([
                'delete_message' => 'đã được',
                'action_type' => 'xóa',
                'delete_end' => 'thành công!',
                'movie_title' => $movie_title
            ]);
        } catch (\Exception $e) {
            // Trả về thông báo lỗi nếu có vấn đề
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Remove multiple movies at once
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request)
    {
        try {
            $movieIds = json_decode($request->movie_ids, true);

            if (empty($movieIds)) {
                return redirect()->back()->with('error', 'Không có phim nào được chọn để xóa');
            }

            $deletedCount = 0;
            $movieTitles = [];

            foreach ($movieIds as $id) {
                $movie = Movie::find($id);

                if ($movie) {
                    $movieTitles[] = $movie->title;

                    // Xóa hình ảnh
                    if (!empty($movie->image)) {
                        // Chỉ xóa file từ server nếu không phải là URL
                        if (substr($movie->image, 0, 5) != 'https') {
                            $image_path = public_path('uploads/movie/' . $movie->image);
                            if (file_exists($image_path)) {
                                unlink($image_path);
                            }
                        }
                    }

                    // Xóa quan hệ với thể loại
                    Movie_Genre::whereIn('movie_id', [$movie->id])->delete();

                    // Xóa tập phim
                    Episode::whereIn('movie_id', [$movie->id])->delete();

                    // Xóa phim
                    $movie->delete();

                    $deletedCount++;
                }
            }

            if ($deletedCount > 0) {
                $message = $deletedCount == 1
                    ? 'Đã xóa phim "' . $movieTitles[0] . '" thành công'
                    : 'Đã xóa ' . $deletedCount . ' phim thành công';

                return redirect()->back()->with([
                    'delete_message' => 'đã được',
                    'action_type' => 'xóa',
                    'delete_end' => 'thành công!',
                    'movie_title' => $deletedCount == 1 ? $movieTitles[0] : $deletedCount . ' phim'
                ]);
            } else {
                return redirect()->back()->with('error', 'Không thể xóa phim');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}