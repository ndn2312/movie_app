<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('admincp.user.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admincp.user.form', compact('user'));
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
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:user,admin',
        ];

        // Chỉ kiểm tra password nếu được nhập
        if ($request->password) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Chỉ cập nhật password nếu được nhập
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with([
            'success_message' => 'đã được',
            'action_type' => 'cập nhật',
            'success_end' => 'thành công!',
            'user_name' => $request->name
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
        $user = User::findOrFail($id);
        $userName = $user->name;

        // Ngăn chặn xóa chính tài khoản đang đăng nhập
        if (auth()->id() == $id) {
            return redirect()->back()->with([
                'error' => 'Không thể xóa tài khoản đang đăng nhập!'
            ]);
        }

        $user->delete();

        return redirect()->back()->with([
            'delete_message' => 'đã được',
            'action_type' => 'xóa',
            'delete_end' => 'thành công!',
            'user_name' => $userName
        ]);
    }

    /**
     * Xóa nhiều người dùng cùng lúc
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;
        $count = 0;

        if (!empty($ids)) {
            foreach ($ids as $id) {
                if (auth()->id() != $id) { // Không xóa tài khoản đang đăng nhập
                    User::where('id', $id)->delete();
                    $count++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa ' . $count . ' người dùng thành công!'
        ]);
    }

    /**
     * Thay đổi trạng thái active của người dùng
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        Log::info('Change Status Request: ', $request->all());
        Log::info('Request Content-Type: ' . $request->header('Content-Type'));

        try {
            // Lấy dữ liệu từ request, hỗ trợ cả JSON và form data
            $userId = $request->input('user_id');
            $status = $request->input('status');

            Log::info("Processing status change for user ID: $userId, new status: $status");

            // Kiểm tra dữ liệu đầu vào
            if (!$userId) {
                Log::error('Missing user_id parameter');
                return response()->json([
                    'error' => 'Thiếu thông tin người dùng (user_id)'
                ], 400);
            }

            $user = User::findOrFail($userId);
            $user->status = $status;
            $user->save();

            Log::info('User status changed successfully. User ID: ' . $user->id . ', New Status: ' . $user->status);

            return response()->json([
                'success' => 'Trạng thái người dùng đã được cập nhật thành công.',
                'user_id' => $user->id,
                'status' => $user->status
            ]);
        } catch (\Exception $e) {
            Log::error('Error changing user status: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'Đã xảy ra lỗi khi cập nhật trạng thái người dùng.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
