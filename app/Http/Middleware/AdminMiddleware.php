<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        // if (!Auth::check()) {
        //     return redirect()->route('login');
        // }

        // // Kiểm tra quyền admin (giả sử role là 'admin')
        // $user = Auth::user();
        // if ($user->role->name !== 'Admin') {
        //     abort(403, 'Bạn không có quyền truy cập trang này.');
        // }

        // return $next($request);
    }
}
