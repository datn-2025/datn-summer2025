<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Vui lòng nhập địa chỉ email hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự.',
        ]);

        $user = User::with('role')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])->withInput();
        }

        // Kiểm tra trạng thái tài khoản
        if ($user->status == "Chưa kích Hoạt" || $user->status == "Bị Khóa") {
            return back()->withErrors(['email' => 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt.'])->withInput();
        }

        // Kiểm tra quyền admin
        if (!$user->isRoleAdmin()) {
            return redirect()->route('welcome')->with('error', 'Bạn không có quyền truy cập vào trang này.');
        }
        // dd($user);
        // Đăng nhập user
        Auth::login($user);
        // dd(Auth::login($user));
        // dd(Auth::user());
        // Kiểm tra đăng nhập thành công
        if (Auth::check()) {
            // Toastr('success', 'Đăng Nhập Thành Công'); // Nếu dùng Toastr thì bật lại
            Toastr::success('Đăng Nhập Thành Công');
            return redirect()->route('admin.dashboard');
        } else {
            // Toastr('error', 'Đăng Nhập Thất bại');
            return back()->withErrors(['email' => 'Đăng nhập thất bại.'])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
