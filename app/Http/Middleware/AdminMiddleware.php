<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login');
        }

        // if (!Auth::check()) {
        //     return redirect()->route('admin.login')
        //         ->with('error', 'Bạn chưa đăng nhập');
        // }
        // if (!Auth::user()->isAdmin()) {
        //     Auth::logout();
        //     return redirect()->route('admin.login')
        //         ->with('error', 'Bạn không có quyền truy cập vào trang quản trị');
        // }
        // // Kiểm tra tài khoản có đang hoạt động không
        // if (!Auth::user()->isActive()) {
        //     Auth::logout();
        //     return redirect()->route('admin.login')
        //         ->with('error', 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt');
        // }

        return $next($request);
    }

}
