<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;


class CountryController extends Controller
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
        $query = Country::orderBy('id', 'DESC');

        // Nếu có từ khóa tìm kiếm, thêm điều kiện tìm kiếm
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                    ->orWhere('slug', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        // Lấy danh sách quốc gia với phân trang, mỗi trang 6 mục
        $list = $query->paginate(6);

        // Giữ lại thông tin tìm kiếm khi phân trang
        $list->appends(['search' => $search]);

        // Nếu là request Ajax, trả về JSON
        if ($request->ajax() || $request->input('ajax') == 1) {
            // Tạo HTML cho grid quốc gia
            $html = '';
            foreach ($list as $country) {
                $html .= '<div class="country-card">';
                $html .= '<div class="country-card-header">';
                $html .= '<h4 class="country-title">' . $country->title . '</h4>';
                $html .= '<div class="country-status ' . ($country->status == 1 ? 'active' : 'inactive') . '">';
                $html .= '<i class="fas fa-circle"></i> ' . ($country->status == 1 ? 'Hiển thị' : 'Ẩn') . '</div>';
                $html .= '</div>';

                $html .= '<div class="country-info">';
                $html .= '<div class="info-group">';
                $html .= '<label><i class="fas fa-info-circle"></i> Mô tả:</label>';
                $html .= '<p class="description">' . ($country->description ? $country->description : 'Chưa có mô tả') . '</p>';
                $html .= '</div>';

                $html .= '<div class="info-group">';
                $html .= '<label><i class="fas fa-link"></i> Đường dẫn:</label>';
                $html .= '<p class="slug">' . $country->slug . '</p>';
                $html .= '</div></div>';

                $html .= '<div class="country-actions">';
                $html .= '<a href="' . route('country.edit', $country->id) . '" class="btn btn-warning btn-sm">';
                $html .= '<i class="fas fa-edit"></i> Sửa</a>';

                $html .= '<form method="POST" action="' . route('country.destroy', $country->id) . '" class="d-inline delete-form">';
                $html .= csrf_field();
                $html .= method_field('DELETE');
                $html .= '<button type="submit" class="btn btn-danger btn-sm delete-btn">';
                $html .= '<i class="fas fa-trash-alt"></i> Xóa</button>';
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

        return view('admincp.country.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admincp.country.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Đầu tiên, tìm quốc gia đã xóa mềm nếu có
        $existingTrashed = Country::onlyTrashed()
            ->where('title', $request->title)
            ->first();

        if ($existingTrashed) {
            // Khôi phục quốc gia đã xóa thay vì tạo mới
            $existingTrashed->restore();

            // Cập nhật các thông tin khác
            $existingTrashed->slug = $request->slug;
            $existingTrashed->description = $request->description;
            $existingTrashed->status = $request->status;
            $existingTrashed->save();

            return redirect()->route('country.index')->with([
                'success' => true,
                'action' => 'Khôi phục',
                'item_name' => $existingTrashed->title,
                'item_type' => 'quốc gia đã xóa trước đó'
            ]);
        }

        // Nếu không có quốc gia trùng tên đã xóa, tiến hành validation
        $data = $request->validate([
            'title' => 'required|unique:countries,title,NULL,id,deleted_at,NULL|max:255',
            'slug' => 'required|unique:countries,slug,NULL,id,deleted_at,NULL|max:255',
            'status' => 'required|boolean',
            'description' => 'nullable|max:256',
        ], [
            'title.required' => 'Tên quốc gia không được để trống',
            'title.unique' => 'Tên quốc gia đã tồn tại',
            'slug.required' => 'Slug không được để trống',
            'slug.unique' => 'Slug đã tồn tại',
            'status.required' => 'Trạng thái không được để trống',
            'status.boolean' => 'Trạng thái không hợp lệ',
        ]);

        // Tạo quốc gia mới
        $country = new Country();
        $country->title = $data['title'];
        $country->slug = $data['slug'];
        $country->description = $data['description'];
        $country->status = $data['status'];
        $country->save();

        return redirect()->route('country.index')->with([
            'success' => true,
            'action' => 'thêm',
            'item_name' => $country->title,
            'item_type' => 'quốc gia'
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
        $country = Country::find($id);
        $list = Country::all();
        return view('admincp.country.form', compact('list', 'country'));
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
        $country = Country::find($id);
        $country->title = $data['title'];
        $country->slug = $data['slug'];
        $country->description = $data['description'];
        $country->status = $data['status'];
        $country->save();

        return redirect()->route('country.index')->with([
            'success' => true,
            'action' => 'cập nhật',
            'item_name' => $country->title,
            'item_type' => 'quốc gia'
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
        $country = Country::find($id);
        $countryName = $country->title;
        $country->delete();

        return redirect()->route('country.index')->with([
            'success' => true,
            'action' => 'xóa',
            'item_name' => $countryName,
            'item_type' => 'quốc gia'
        ]);
    }
}
