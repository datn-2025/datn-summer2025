<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $guard = Auth::guard('admin');
        if (!$guard->check()) {
            abort(403, 'Bạn chưa đăng nhập');
        }
        $user = $guard->user();
        if (!$user->isAdmin()) {
            $guard->logout();
           abort(403, 'Bạn không có quyền truy cập vào trang quản trị');
        }

        if (!$user->isActive()) {
            $guard->logout();
            abort(403, 'Tài khoản của bạn đã bị khoá hoặc chưa kích hoạt');
        }

        return $next($request);
    }
}
