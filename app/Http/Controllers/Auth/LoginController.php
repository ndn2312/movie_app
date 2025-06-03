<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login'; // tên field trong form
    }

    /**
     * Xác định field dùng để đăng nhập (email hoặc name)
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('login');

        // Kiểm tra nếu input là email
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        return [
            $field => $login,
            'password' => $request->input('password')
        ];
    }

    /**
     * Validate login request
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Kiểm tra giới hạn đăng nhập
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Lấy thông tin đăng nhập (email hoặc name)
        $credentials = $this->credentials($request);

        // Thử đăng nhập
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Thành công, lấy người dùng hiện tại
            $user = Auth::user();

            // Kiểm tra trạng thái tài khoản
            if ($user->status == 0) {
                // Tài khoản bị khóa, đăng xuất ngay lập tức
                Auth::logout();

                // Invalidate session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Trả về JSON nếu yêu cầu AJAX
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.'
                    ], 403);
                }

                return redirect()->back()
                    ->withInput($request->only('login', 'remember'))
                    ->withErrors([
                        'login' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.'
                    ]);
            }

            // Refresh session
            $request->session()->regenerate();

            // Trả về JSON nếu yêu cầu AJAX
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $user->role === 'admin' ? route('home') : route('homepage')
                ]);
            }

            // Chuyển hướng dựa vào vai trò
            if ($user->role === 'admin') {
                return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
            }

            return redirect()->route('homepage')->with('success', 'Đăng nhập thành công!');
        }

        // Đăng nhập thất bại, tăng số lần thử
        $this->incrementLoginAttempts($request);

        // Trả về lỗi
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('homepage');
    }

    /**
     * Handle an admin login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function adminLogin(Request $request)
    // {
    //     $this->validateLogin($request);

    //     // Kiểm tra giới hạn đăng nhập
    //     if (
    //         method_exists($this, 'hasTooManyLoginAttempts') &&
    //         $this->hasTooManyLoginAttempts($request)
    //     ) {
    //         $this->fireLockoutEvent($request);
    //         return $this->sendLockoutResponse($request);
    //     }

    //     // Lấy thông tin đăng nhập (email hoặc name)
    //     $credentials = $this->credentials($request);

    //     // Thử đăng nhập
    //     if (Auth::attempt($credentials, $request->filled('remember'))) {
    //         // Thành công, lấy người dùng hiện tại
    //         $user = Auth::user();

    //         // Kiểm tra trạng thái tài khoản
    //         if ($user->status == 0) {
    //             // Tài khoản bị khóa, đăng xuất ngay lập tức
    //             Auth::logout();
    //             $request->session()->invalidate();
    //             $request->session()->regenerateToken();

    //             return redirect()->route('admin.login')
    //                 ->withErrors(['login' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên để được hỗ trợ.']);
    //         }

    //         // Refresh session
    //         $request->session()->regenerate();

    //         // Kiểm tra nếu không phải admin
    //         if ($user->role !== 'admin') {
    //             Auth::logout();
    //             $request->session()->invalidate();
    //             $request->session()->regenerateToken();

    //             return redirect()->route('admin.login')
    //                 ->withErrors(['login' => 'Tài khoản này không có quyền truy cập trang quản trị.']);
    //         }

    //         return redirect()->route('home')
    //             ->with('success', 'Đăng nhập trang quản trị thành công!');
    //     }

    //     // Đăng nhập thất bại
    //     $this->incrementLoginAttempts($request);

    //     return redirect()->route('admin.login')
    //         ->withErrors(['login' => 'Thông tin đăng nhập không chính xác.']);
    // }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Tên đăng nhập/email hoặc mật khẩu không chính xác.'
            ], 401);
        }

        return redirect()->back()
            ->withInput($request->only('login', 'remember'))
            ->withErrors([
                'login' => [trans('auth.failed')],
            ]);
    }
}