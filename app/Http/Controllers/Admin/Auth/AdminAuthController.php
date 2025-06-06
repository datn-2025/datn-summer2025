<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->isAdmin()) {
                Auth::logout();
                Toastr::error('Bạn không có quyền truy cập vào trang quản trị');
                return redirect()->back()->withInput();
                // return back()->withErrors([
                //     'email' => 'Bạn không có quyền truy cập vào trang quản trị',
                // ]);
            }

            if (!$user->isActive()) {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt',
                ]);
            }

            $request->session()->regenerate();
            $request->session()->put('admin_name', $user->name);
            Toastr::success('Đăng nhập thành công!');
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()
            ->withErrors(['error' => 'Sai tài khoản hoặc mật khẩu'])
            ->withInput();

        // if (!Hash::check($request->password, $user->password)) {
        //    return back()->withErrors([
        //         'email' => ['Email không chính xác.'],
        //         'password' => ['Mật khẩu không chính xác.'],
        //     ]);
        // }

        // $request->session()->put([
        //     'admin_id' => $user->id,
        //     'admin_name' => $user->name,
        //     'admin_role' => $user->role->name
        // ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Toastr::success('Đăng xuất thành công!');
        return redirect()->route('admin.login');
    }
}
