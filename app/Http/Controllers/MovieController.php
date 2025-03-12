<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;

use App\Models\Genre;

use App\Models\Country;
use Carbon\Carbon;

use PHPUnit\Framework\Constraint\Count;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Movie::with('category', 'genre', 'country')->orderBy('id', 'DESC')->get()
        return view('admincp.movie.index', compact('list'))
    }

    public function update_year(Request $request){
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);

        $movie->year = $data['year'];
        $movie->save();
    }
    public function update_topview(Request $request){
        $data = $request->all();
        $movie = Movie::find($data['id_phim']);

        $movie->topview = $data['topview'];
        $movie->save();
    }
    public function filter_topview(Request $request){
        
    $data = $request->all();
    $movie = Movie::where('topview', $data['value'])
        ->orderBy('ngaycapnhat', 'DESC')
        ->take(50)
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
        } else {
            $text = 'FullHD';
        }
        
        // Tạo HTML cho mỗi phim
        $output.= '<div class="item">
                    <a href="'.url('phim/'.$mov->slug).'" title="'.$mov->title.'">
                       <div class="item-link">
                          <img src="'.url('uploads/movie/'.$mov->image).'" class="lazy post-thumb" alt="'.$mov->title.'" title="'.$mov->title.'"/>
                          <span class="is_trailer">'.$text.'</span>
                       </div>
                       <p class="title">'.$mov->title.'</p>
                    </a>
                    <div class="viewsCount" style="color: #9d9d9d;">3.2K lượt xem</div>
                    <div style="float: left;">
                       <span class="user-rate-image post-large-rate stars-large-vang" style="display: block;/* width: 100%; */">
                       <span style="width: 0%"></span>
                       </span>
                    </div>
                </div>';
    }
    
    echo $output;
}

public function filter_default(Request $request){
        
    $data = $request->all();
    $movie = Movie::where('topview',0)
        ->orderBy('ngaycapnhat', 'DESC')
        ->take(50)
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
        } else {
            $text = 'FullHD';
        }
        
        // Tạo HTML cho mỗi phim
        $output.= '<div class="item">
                    <a href="'.url('phim/'.$mov->slug).'" title="'.$mov->title.'">
                       <div class="item-link">
                          <img src="'.url('uploads/movie/'.$mov->image).'" class="lazy post-thumb" alt="'.$mov->title.'" title="'.$mov->title.'"/>
                          <span class="is_trailer">'.$text.'</span>
                       </div>
                       <p class="title">'.$mov->title.'</p>
                    </a>
                    <div class="viewsCount" style="color: #9d9d9d;">3.2K lượt xem</div>
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
        $country = Country::pluck('title', 'id');

        return view('admincp.movie.form', compact('genre', 'country', 'category'));
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
        $movie = new Movie();
        $movie->title = $data['title'];
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
        $movie->genre_id = $data['genre_id'];
        $movie->country_id = $data['country_id'];
        $movie->ngaytao = Carbon::now('Asia/Ho_Chi_Minh');
        $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');


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
        }
        
        $movie->save();
        
        // Truyền thông tin để hiển thị thông báo
        return redirect()->back()->with([
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
        $movie = Movie::find($id);
        return view('admincp.movie.form', compact('genre', 'country', 'category', 'movie'));
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
            $movie->tags = $data['tags'];

            $movie->thoiluong = $data['thoiluong'];
            $movie->resolution = $data['resolution'];
            $movie->phim_hot = $data['phim_hot'];
            $movie->phude = $data['phude'];
            $movie->slug = $data['slug'];
            $movie->description = $data['description'];
            $movie->status = $data['status'];
            $movie->category_id = $data['category_id'];
            $movie->genre_id = $data['genre_id'];
            $movie->country_id = $data['country_id'];
            $movie->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');

            $movie->save();

            $get_image = $request->file('image');

            if ($get_image) {
                if (!empty($movie->image) && file_exists('uploads/movie/' . $movie->image)) {
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
            }
            return redirect()->back()->with([
                'success_message' => 'đã được',
                'action_type' => 'cập nhật',
                'success_end' => 'thành công!',
                'movie_title' => $data['title']
            ]);
        } catch (\Exception $e) {
            // Trả về thông báo lỗi nếu có vấn đề
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage());
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
                $image_path = public_path('uploads/movie/' . $movie->image);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            
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
}