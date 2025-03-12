<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $list = Genre::all();
        return view('admincp.genre.form', compact('list'));
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
    
    // Kiểm tra xem có thể loại đã xóa nào trùng tên không
    $existingTrashed = Genre::onlyTrashed()
        ->where('title', $data['title'])
        ->first();

    if ($existingTrashed) {
        // Khôi phục thể loại đã xóa thay vì tạo mới
        $existingTrashed->restore();
        
        // Cập nhật các thông tin khác
        $existingTrashed->slug = $data['slug'];
        $existingTrashed->description = $data['description'];
        $existingTrashed->status = $data['status'];
        $existingTrashed->save();
        
        return redirect()->back()->with([
            'success' => true,
            'action' => 'Khôi phục',
            'item_name' => $existingTrashed->title,
            'item_type' => 'thể loại đã xóa trước đó'
        ]);
    }

    // Nếu không có thể loại trùng tên đã xóa, tạo mới như bình thường
    $genre = new Genre();
    $genre->title = $data['title'];
    $genre->slug = $data['slug'];
    $genre->description = $data['description'];
    $genre->status = $data['status'];
    $genre->save();
    
    return redirect()->back()->with([
        'success' => true,
        'action' => 'thêm',
        'item_name' => $genre->title,
        'item_type' => 'thể loại'
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
        $genre = Genre::find($id);
        $list = Genre::all();
        return view('admincp.genre.form', compact('list', 'genre'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //cũ//
    // public function update(Request $request, $id)
    // {
    //     $data = $request->all();
    //     $genre =  Genre::find($id);
    //     $genre->title = $data['title'];
    //     $genre->slug = $data['slug'];

    //     $genre->description = $data['description'];
    //     $genre->status = $data['status'];
    //     $genre->save();
    //     return redirect()->back();
    // }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $genre = Genre::find($id);
        $genre->title = $data['title'];
        $genre->slug = $data['slug'];
        $genre->description = $data['description'];
        $genre->status = $data['status'];
        $genre->save();
        
        return redirect()->back()->with([
            'success' => true,
            'action' => 'cập nhật',
            'item_name' => $genre->title,
            'item_type' => 'thể loại'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //cũ//
    // public function destroy($id)
    // {
    //     Genre::find($id)->delete();
    //     return redirect()->back();
    // }
    public function destroy($id)
    {
        $genre = Genre::find($id);
        $genreName = $genre->title;
        $genre->delete();
        
        return redirect()->back()->with([
            'success' => true,
            'action' => 'xóa',
            'item_name' => $genreName,
            'item_type' => 'thể loại'
        ]);
    }
}