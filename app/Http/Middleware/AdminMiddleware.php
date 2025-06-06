<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Bạn chưa đăng nhập');
        }
        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Bạn không có quyền truy cập vào trang quản trị');
        }

        if (!Auth::user()->isActive()) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt');
        }

        return $next($request);
    }
}
