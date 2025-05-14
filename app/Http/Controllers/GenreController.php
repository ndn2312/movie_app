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
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ request
        $search = $request->input('search');

        // Khởi tạo query builder
        $query = Genre::orderBy('id', 'DESC');

        // Nếu có từ khóa tìm kiếm, thêm điều kiện tìm kiếm
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('slug', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        // Lấy danh sách thể loại với phân trang, mỗi trang 6 mục
        $list = $query->paginate(6);

        // Giữ lại thông tin tìm kiếm khi phân trang
        $list->appends(['search' => $search]);

        // Nếu là request Ajax, trả về JSON
        if ($request->ajax() || $request->input('ajax') == 1) {
            // Tạo HTML cho grid thể loại
            $html = '';
            foreach ($list as $genre) {
                $html .= '<div class="genre-card">';
                $html .= '<div class="genre-card-header">';
                $html .= '<h3 class="genre-title">' . $genre->title . '</h3>';
                $html .= '<div class="genre-status ' . ($genre->status == 1 ? 'active' : 'inactive') . '">';
                $html .= '<i class="fas fa-circle"></i> ' . ($genre->status == 1 ? 'Hiển thị' : 'Ẩn') . '</div>';
                $html .= '</div>';

                $html .= '<div class="genre-info"><div>';
                $html .= '<div class="info-group">';
                $html .= '<label><i class="fas fa-link"></i> Slug</label>';
                $html .= '<p class="slug">' . $genre->slug . '</p>';
                $html .= '</div>';

                $html .= '<div class="info-group">';
                $html .= '<label><i class="fas fa-align-left"></i> Mô tả</label>';
                $html .= '<p class="description">' . ($genre->description ? $genre->description : 'Không có mô tả') . '</p>';
                $html .= '</div></div></div>';

                $html .= '<div class="genre-actions">';
                $html .= '<a href="' . route('genre.edit', $genre->id) . '" class="btn btn-primary">';
                $html .= '<i class="fas fa-edit"></i> Sửa</a>';

                $html .= '<form method="POST" action="' . route('genre.destroy', $genre->id) . '" class="delete-form">';
                $html .= csrf_field();
                $html .= method_field('DELETE');
                $html .= '<button type="submit" class="btn btn-danger delete-btn">';
                $html .= '<i class="fas fa-trash"></i> Xóa</button>';
                $html .= '</form>';
                $html .= '</div>';
                $html .= '</div>';
            }

            return response()->json([
                'html' => $html,
                'pagination' => $list->links()->toHtml(),
                'total' => $list->total()
            ]);
        }

        return view('admincp.genre.index', compact('list'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $list = Genre::all();
        return view('admincp.genre.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // Đầu tiên, tìm thể loại đã xóa mềm nếu có
        $existingTrashed = Genre::onlyTrashed()
            ->where('title', $request->title)
            ->first();

        if ($existingTrashed) {
            // Khôi phục thể loại đã xóa thay vì tạo mới
            $existingTrashed->restore();

            // Cập nhật các thông tin khác
            $existingTrashed->slug = $request->slug;
            $existingTrashed->description = $request->description;
            $existingTrashed->status = $request->status;
            $existingTrashed->save();

            return redirect()->back()->with([
                'success' => true,
                'action' => 'Khôi phục',
                'item_name' => $existingTrashed->title,
                'item_type' => 'thể loại đã xóa trước đó'
            ]);
        }

        // Nếu không có thể loại trùng tên đã xóa, tiến hành validation
        $data = $request->validate([
            'title' => 'required|unique:genres,title,NULL,id,deleted_at,NULL|max:255',
            'slug' => 'required|unique:genres,slug,NULL,id,deleted_at,NULL|max:255',
            'status' => 'required|boolean',
            'description' => 'nullable|max:256',
        ], [
            'title.required' => 'Tên thể loại không được để trống',
            'title.unique' => 'Tên thể loại đã tồn tại',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'status.required' => 'Trạng thái không được để trống',
            'status.boolean' => 'Trạng thái không hợp lệ',
        ]);

        // Tạo thể loại mới
        $genre = new Genre();
        $genre->title = $data['title'];
        $genre->slug = $data['slug'];
        $genre->description = $data['description'];
        $genre->status = $data['status'];
        $genre->save();

        return redirect()->route('genre.index')->with([
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