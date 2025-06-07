<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->withErrors(['login' => 'Bạn chưa đăng nhập']);
        }
        if (!Auth::guard('admin')->user()->isAdmin()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->withErrors(['login' => 'Bạn không có quyền truy cập vào trang quản trị']);
        }

        if (!Auth::guard('admin')->user()->isActive()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')
                ->withErrors(['login' => 'Tài khoản của bạn đã bị khoá hoặc chưa kích hoạt']);
        }

        return $next($request);
    }
}
