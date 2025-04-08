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
    public function index()
    {
        $list_episode = Episode::with('movie')->orderBy('movie_id', 'DESC')->get();
        // return response()->json($list_episode);
        return view('admincp.episode.index',compact('list_episode'));
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
    
    $ep = new Episode();
    $ep->movie_id = $data['movie_id'];
    $ep->linkphim = $data['link'];
    $ep->episode = $data['episode'];
    $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
    $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
    $ep->save();
    
    // Lấy URL trước đó và kiểm tra
    $referer = request()->headers->get('referer');
    
    // Thông báo chung
    $notification = [
        'movie_title' => $data['episode'],
        'success_message' => 'đã được',
        'action_type' => 'thêm',
        'success_end' => 'thành công! ',
    ];
    
    // Nếu từ trang add_episode, giữ nguyên trang
    if (strpos($referer, 'add_episode') !== false) {
        return redirect()->back()->with($notification);
    } else {
        // Nếu từ trang khác, chuyển về index
        return redirect()->to('episode')->with($notification);
    }
}

    public function add_episode($id)
    
    {
        $movie = Movie::find($id);
        $list_episode = Episode::with('movie')->where('movie_id', $id)->orderBy('episode','DESC')->get();
        return view('admincp.episode.add_episode', compact('list_episode','movie'));
       
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
        $ep =  Episode::find($id);
        $ep->movie_id = $data['movie_id'];
        $ep->linkphim = $data['link'];
        $ep->episode = $data['episode'];
        $ep->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $ep->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $ep->save(); 
        return redirect()->to('episode')->with([
            'success_message' => 'đã được',
            'action_type' => 'cập nhật',
            'success_end' => 'thành công!',
            'movie_title' => $data['episode']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $ep = Episode::find($id);
        $ep_id = $ep->episode;
        $ep->delete();
        return redirect()->back()->with([
            'movie_title' => $ep_id,
            'delete_message' => 'đã được',
            'action_type' => 'xóa',
            'delete_end' => 'thành công!',
            
        ]);

    }

    public function select_movie()
    {
        $id = $_GET['id'];
        $movie = Movie::find($id);
        $output = '<option value="">---Chọn tập phim---</option>';
        if($movie->thuocphim == 'phimbo'){
            for ($i = 1; $i <= $movie->sotap; $i++) {
                $output .= '<option value="' . $i . '">Tập ' . $i . '</option>';
            };
        } else{
            $output .= '<option value="HD">HD</option>
            <option value="FullHD">FullHD</option>
            <option value="Cam">Cam</option>';

        }
        
        echo $output;
    }
}