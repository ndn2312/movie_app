<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Genre;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Hiển thị trang thông tin tài khoản
     */
    public function index()
    {
        $user = Auth::user();
        $genre = Genre::orderBy('id', 'ASC')->get();
        $country = Country::orderBy('id', 'ASC')->get();
        $category = Category::orderBy('id', 'ASC')->get();
        return view('pages.account', compact('user', 'genre', 'country', 'category'));
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'gender' => 'nullable|string|in:male,female,other',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cập nhật thông tin người dùng
        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thông tin tài khoản đã được cập nhật thành công!'
            ]);
        }

        return redirect()->route('account')->with('success', 'Thông tin tài khoản đã được cập nhật thành công!');
    }

    /**
     * Thay đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác'])
                ->withInput();
        }

        // Cập nhật mật khẩu
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('account')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }

    /**
     * Tải lên ảnh đại diện
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $image = $request->file('avatar');
        $filename = time() . '.' . $image->getClientOriginalExtension();

        // Kiểm tra môi trường
        if (app()->environment('local', 'development')) {
            // Phương thức lưu trên môi trường local/development

            // Xóa avatar cũ nếu không phải avatar mặc định
            if ($user->avatar && !str_contains($user->avatar, 'ui-avatars.com')) {
                $oldPath = str_replace(url('/storage'), 'public', $user->avatar);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }

            // Upload avatar mới vào storage/app/public
            $path = Storage::disk('public')->put('avatars/' . $filename, file_get_contents($image->getRealPath()));
            $avatarUrl = Storage::url('avatars/' . $filename);
        } else {
            // Phương thức lưu trên môi trường production (ngoài dự án)

            // Xóa avatar cũ nếu không phải avatar mặc định
            if ($user->avatar && !str_contains($user->avatar, 'ui-avatars.com')) {
                $oldPath = $user->avatar;
                $oldFilename = basename($oldPath);
                $oldFullPath = dirname(base_path()) . '/images/avatar/' . $oldFilename;
                if (file_exists($oldFullPath)) {
                    unlink($oldFullPath);
                }
            }

            // Upload avatar mới vào thư mục ngoài dự án
            $destinationPath = dirname(base_path()) . '/images/avatar';

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $filename);
            $avatarUrl = url('../images/avatar/' . $filename);
        }

        // Cập nhật URL avatar
        User::where('id', $user->id)->update([
            'avatar' => $avatarUrl
        ]);

        return redirect()->route('account')->with('success', 'Ảnh đại diện đã được cập nhật thành công!');
    }
}
