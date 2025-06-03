<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu người dùng không đăng nhập
        if (!Auth::check()) {
            return redirect()->route('homepage')
                ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        // Kiểm tra nếu người dùng có quyền admin
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Người dùng không có quyền admin, chỉ chuyển hướng về trang chủ không đăng xuất
        return redirect()->route('homepage')
            ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
    }
}
 