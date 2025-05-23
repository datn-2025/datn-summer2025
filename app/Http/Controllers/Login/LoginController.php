<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;

class LoginController extends Controller
{
    // Trang tài khoản: Nếu chưa đăng nhập thì chuyển sang trang đăng nhập
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('account.login');
        }
        return view('account.index');
    }

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('account.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            Toastr::success('Đăng nhập thành công!', 'Thành công');
            return redirect()->intended('/admin');
        }

        Toastr::error('Thông tin đăng nhập không chính xác.', 'Lỗi');
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->withInput();
    }

    // Hiển thị form đăng ký
    public function register()
    {
        return view('account.register');
    }

    // Xử lý đăng ký
    public function handleRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập tên đăng nhập.',
            'name.max' => 'Tên đăng nhập tối đa 255 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Lấy role_id của quyền "User"
        $userRole = Role::where('name', 'User')->first();
        if (!$userRole) {
            Toastr::error('Không tìm thấy quyền User trong hệ thống!', 'Lỗi');
            return back()->withErrors(['role' => 'Không tìm thấy quyền User trong hệ thống!']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $userRole->id,
        ]);

        Auth::login($user);
        Toastr::success('Đăng ký tài khoản thành công!', 'Thành công');
        return redirect('/admin');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Toastr::success('Đăng xuất thành công!', 'Thành công');
        return redirect('/');
    }
}
