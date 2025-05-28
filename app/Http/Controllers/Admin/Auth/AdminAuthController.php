<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
                return back()->withErrors([
                    'email' => 'Bạn không có quyền truy cập vào trang quản trị',
                ]);
            }

            if (!$user->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt',
                ]);
            }

            $request->session()->regenerate();
            $request->session()->put('admin_name', $user->name);
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác',
        ]);

        // $user = User::with('role')->where('email', $request->email)->first();

        // // if (!$user|| $user->role_id != "702e2e4d-ae16-31eb-81c8-8224d58e9e9f") {
        // if (!$user|| $user->role->name != "Admin") {
        //    return back()->withErrors([
        //         'email' => ['Bạn không có quyền truy cập vào trang quản trị'],
        //     ]);
        // }

        // if ($user->status != "Hoạt Động") {
        //    return back()->withErrors([
        //         'email' => ['Tài khoản của bạn đã bị khóa hoặc chưa được kích hoạt.'],
        //     ]);
        // }

        // if (!Hash::check($request->password, $user->password)) {
        //    return back()->withErrors([
        //         'email' => ['Email không chính xác.'],
        //         'password' => ['Mật khẩu không chính xác.'],
        //     ]);
        // }

        // $request->session()->regenerate();
        // $request->session()->put([
        //     'admin_id' => $user->id,
        //     'admin_name' => $user->name,
        //     'admin_role' => $user->role->name
        // ]);
        // return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        // return redirect()->route('admin.login');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
