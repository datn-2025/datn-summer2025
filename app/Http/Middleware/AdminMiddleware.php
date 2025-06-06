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
                ->withErrors(['login' => 'Bạn chưa đăng nhập']);
        }
        if (!Auth::user()->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['login' => 'Bạn không có quyền truy cập vào trang quản trị']);
        }

        if (!Auth::user()->isActive()) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['login' => 'Tài khoản của bạn đã bị khoá hoặc chưa kích hoạt']);
        }

        return $next($request);
    }
}
