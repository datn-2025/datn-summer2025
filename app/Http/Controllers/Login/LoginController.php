<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationMail;

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
        // dd($request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
        ]);

        // Check user status before attempting login
        $user = User::where('email', $request->email)->first();
        // dd(($user));
        if ($user) {
            if ($user->status === 'Bị Khóa') {

                Toastr::error('Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.', 'Lỗi');
                return back();
            } elseif ($user->status === 'Chưa kích Hoạt') {
                Toastr::error('Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt.', 'Lỗi');
                return back();
            }

            // Kiểm tra số lần đăng nhập sai
            $attempts = session('login_attempts_' . $user->id, 0);
            if ($attempts >= 3) {
               
                $user->status = 'Bị Khóa';
                $user->save();
                Toastr::error('Tài khoản đã bị khóa do đăng nhập sai quá nhiều lần. Vui lòng liên hệ quản trị viên.', 'Lỗi');
                return back();
            }
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            // Xóa đếm số lần đăng nhập sai khi đăng nhập thành công
            if ($user) {
                session()->forget('login_attempts_' . $user->id);
            }
            Toastr::success('Đăng nhập thành công!', 'Thành công');
            return redirect()->intended('/admin');
        }

        // Tăng số lần đăng nhập sai
        if ($user) {
            // dd(($user));
            session(['login_attempts_' . $user->id => $attempts + 1]);
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
        // dd($request->all());
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
            'status' => 'Chưa kích Hoạt',
        ]);

        // Tạo activation token và url
        $token = sha1($user->email);
        $activationUrl = route('account.activate', [
            'userId' => $user->id,
            'token' => $token,
            'expires' => now()->addHours(24)->timestamp
        ]);

        // Gửi email kích hoạt
        try {
            Mail::to($user->email)->send(new ActivationMail($activationUrl, $user->name));
            Toastr::success('Đăng ký tài khoản thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.', 'Thành công');
        } catch (\Exception $e) {
            dd($e);
            Toastr::error('Không thể gửi email kích hoạt. Vui lòng thử lại sau.', 'Lỗi');
            $user->delete(); // Xóa user nếu không gửi được email
            return back()->withInput();
        }

        return redirect()->route('account.login');
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
