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
        $list = Country::all();
        return view('admincp.country.form', compact('list'));
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
        $existingTrashed = Country::onlyTrashed()
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
        $country = new Country();
        $country->title = $data['title'];
        $country->slug = $data['slug'];
        $country->description = $data['description'];
        $country->status = $data['status'];
        $country->save();
        
        return redirect()->back()->with([
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
        
        return redirect()->back()->with([
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
        
        return redirect()->back()->with([
            'success' => true,
            'action' => 'xóa',
            'item_name' => $countryName,
            'item_type' => 'quốc gia'
        ]);
    }
}