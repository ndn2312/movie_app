<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;

class CategoryController extends Controller
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
        $list = Category::orderBy('position','ASC')->get();
        return view('admincp.category.form', compact('list'));
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
    
        // Kiểm tra xem có danh mục đã xóa nào trùng tên không
        $existingTrashed = Category::onlyTrashed()
            ->where('title', $data['title'])
            ->first();
    
        if ($existingTrashed) {
            // Khôi phục danh mục đã xóa thay vì tạo mới
            $existingTrashed->restore();
    
            // Cập nhật các thông tin khác
            $existingTrashed->slug = $data['slug'];
            $existingTrashed->description = $data['description'];
            $existingTrashed->status = $data['status'];
            $existingTrashed->position = Category::max('position') + 1; // Đặt ở cuối danh sách
            $existingTrashed->save();
    
            return redirect()->back()->with([
                'success' => true,
                'action' => 'Khôi phục',
                'item_name' => $existingTrashed->title,
                'item_type' => 'danh mục đã xóa trước đó'
            ]);
        }
    
        // Nếu không có danh mục trùng tên đã xóa, tạo mới như bình thường
        $category = new Category();
        $category->title = $data['title'];
        $category->slug = $data['slug'];
        $category->description = $data['description'];
        $category->status = $data['status'];
        $category->ngaytao = Carbon::now('Asia/Ho_Chi_Minh');
        $category->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
        $category->position = Category::max('position') + 1; // THÊM DÒNG NÀY để đặt vị trí mới ở cuối
        $category->save();
    
        return redirect()->back()->with([
            'success' => true,
            'action' => 'thêm',
            'item_name' => $category->title,
            'item_type' => 'danh mục'
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
        $category = Category::find($id);
        $list = Category::orderBy('position','ASC')->get();
        return view('admincp.category.form', compact('list', 'category'));
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
        $category =  Category::find($id);
        $category->title = $data['title'];
        $category->slug = $data['slug'];

        $category->description = $data['description'];
        $category->status = $data['status'];
        $category->ngaycapnhat = Carbon::now('Asia/Ho_Chi_Minh');
        $category->save();
        return redirect()->back()->with([
            'success' => true,
            'action' => 'cập nhật',
            'item_name' => $category->title,
            'item_type' => 'danh mục'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function resorting(Request $request) {
        $data = $request->all(); // Lấy toàn bộ dữ liệu từ request
    
        foreach ($data['array_id'] as $key => $value) {
            $category = Category::find($value); // Tìm category theo ID
            $category->position = $key; // Gán vị trí mới
            $category->save(); // Lưu vào database
        }
        
    }

    public function destroy($id)
    {

        $category = Category::find($id);
        $categoryName = $category->title;
        $category->delete();

        return redirect()->back()->with([
            'success' => true,
            'action' => 'xóa',
            'item_name' => $categoryName,
            'item_type' => 'danh mục'
        ]);
    }
    
}