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
use Illuminate\Support\Facades\Log;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    // Trang tài khoản: Nếu chưa đăng nhập thì chuyển sang trang đăng nhập
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('account.index');
    }

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        // Nếu đã đăng nhập thì chuyển hướng về trang chủ
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('account.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        // Nếu đã đăng nhập thì chuyển hướng về trang chủ
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
        ]);

        // Kiểm tra trạng thái tài khoản trước khi đăng nhập
        $user = User::where('email', $request->email)->first();
        // dd($user);


        if ($user) {
            // Kiểm tra nếu tài khoản bị khóa
            if ($user->status === 'Bị Khóa') {
                Toastr::error('Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.', 'Lỗi');
                return back()->withInput();
            }

            // Kiểm tra nếu tài khoản chưa kích hoạt
            if ($user->status === 'Chưa kích Hoạt') {
                // Gửi lại email kích hoạt nếu cần
                // Mail::to($user->email)->send(new ActivationMail($user));
                Toastr::error('Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt.', 'Lỗi');
                return back()->withInput();
            }

            // Kiểm tra số lần đăng nhập sai
            $attempts = session('login_attempts_' . $user->id, 0);
            if ($attempts >= 3) {
                $user->status = 'Bị Khóa';
                $user->save();
                Toastr::error('Tài khoản đã bị khóa do đăng nhập sai quá nhiều lần. Vui lòng liên hệ quản trị viên.', 'Lỗi');
                return back()->withInput();
            }
        }

        // Thực hiện đăng nhập
        $credentials = $request->only('email', 'password');
        // dd($credentials);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            // Xóa đếm số lần đăng nhập sai
            if (isset($user)) {
                session()->forget('login_attempts_' . $user->id);
            }

            // Kiểm tra nếu là admin thì chuyển hướng về trang admin
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }
            Toastr::success('Đăng nhập thành công!', 'Thành công');
            return redirect()->intended(route('home'));
        }

        // Tăng số lần đăng nhập sai
        if (isset($user)) {
            session(['login_attempts_' . $user->id => ($attempts ?? 0) + 1]);
            $remainingAttempts = 3 - (($attempts ?? 0) + 1);
            if ($remainingAttempts > 0) {
                Toastr::error("Sai thông tin đăng nhập. Bạn còn $remainingAttempts lần thử.", 'Lỗi');
            } else {
                Toastr::error('Tài khoản của bạn đã bị khóa do đăng nhập sai quá nhiều lần.', 'Lỗi');
            }
        } else {
            Toastr::error('Email hoặc mật khẩu không chính xác.', 'Lỗi');
        }

        return back()->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Toastr::success('Bạn đã đăng xuất thành công!', 'Thành công');
        return redirect()->route('home');
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
        'email.unique' => 'Email đã tồn tại. Vui lòng đăng ký bằng email khác!',
        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.min' => 'Mật khẩu tối thiểu 8 ký tự.',
        'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
    ]);

    $userRole = Role::where('name', 'User')->first();
    if (!$userRole) {
        Toastr::error('Không tìm thấy quyền User trong hệ thống!', 'Lỗi');
        return back()->withErrors(['role' => 'Không tìm thấy quyền User trong hệ thống!']);
    }

    $token = sha1($request->email);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $userRole->id,
        'status' => 'Chưa kích Hoạt',
        'activation_token' => $token,
        'activation_expires' => now()->addHours(24),
    ]);
    

    $activationUrl = route('account.activate', [
        'userId' => $user->id,
        'token' => $token,
    ]);
    // dd($activationUrl);
    

    try {
        Mail::to($user->email)->send(new ActivationMail($activationUrl, $user->name));
        Toastr::success('Đăng ký tài khoản thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.', 'Thành công');
    } catch (\Exception $e) {
        $user->delete();
        Toastr::error('Không thể gửi email kích hoạt. Vui lòng thử lại sau.', 'Lỗi');
        return back()->withInput();
    }

    return redirect()->route('login');
}
public function activate(Request $request)
{
    $user = User::find($request->userId);

    if (!$user || $user->activation_token !== $request->token || now()->greaterThan($user->activation_expires)) {
        return redirect()->route('login')->with('error', 'Liên kết không hợp lệ hoặc đã hết hạn.');
    }

    $user->status = 'Hoạt Động';
    $user->activation_token = null;
    $user->activation_expires = null;
    $user->save();
    return redirect()->route('login')->with('success', 'Tài khoản đã được kích hoạt thành công.');
}


    // Đăng xuất
    // public function logout(Request $request)
    // {
    //     Auth::logout();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     Toastr::success('Đăng xuất thành công!', 'Thành công');

    //     return redirect('/');
    // }

    // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        return view('account.resetpass');
    }

    // Gửi email đặt lại mật khẩu
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.exists' => 'Email này không tồn tại trong hệ thống.'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user->status === 'Bị Khóa') {
            Toastr::error('Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.', 'Lỗi');
            return back()->withInput();
        }

        $resetToken = Str::random(64);
        $user->reset_token = $resetToken;
        $user->save();


        $resetLink = route('password.reset', ['token' => $resetToken , 'email' => $request->email]);


        try {
            Mail::to($user->email)->send(new ResetPasswordMail($resetLink));
            Toastr::success('Chúng tôi đã gửi email chứa liên kết đặt lại mật khẩu của bạn!', 'Thành công');
            return back();
        } catch (\Exception $e) {
            $user->reset_token = null;
            $user->save();
            Toastr::error('Không thể gửi email đặt lại mật khẩu. Vui lòng thử lại sau.', 'Lỗi');
            return back()->withInput();
        }
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetPasswordForm($token, $email)
    {
        return view('account.reset-password-form', ['token' => $token, 'email' => $email]);
    }

    // Xử lý đặt lại mật khẩu
    public function handleResetPassword(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            // 'email.required' => 'Vui lòng nhập email.',
            // 'email.email' => 'Email không đúng định dạng.',
            // 'email.exists' => 'Email không tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.'
        ]);

        $user = User::where('email', $request->email)
            ->where('reset_token', $request->token)
            ->first();
        // dd($user);

        if (!$user) {
            Toastr::error('Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn!', 'Lỗi');
            return back()->withErrors(['email' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn!']);
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->save();

        Toastr::success('Mật khẩu đã được thay đổi thành công. Vui lòng đăng nhập lại.', 'Thành công');
        return redirect()->route('login');

    }

    // hiển thị thông tin người dùng khi đang đăng nhập
    public function showUser(Request $request)
    {
        if (!Auth::check()) {
            Toastr::error('Bạn cần đăng nhập để xem thông tin tài khoản.', 'Lỗi');
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('profile.client', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(), // cho phép email cũ
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            $filename = time() . '.' . $request->avatar->extension();

            // Xóa file cũ nếu có
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                unlink(public_path('storage/' . $user->avatar));
            }
            // Lưu file vào storage/app/public/avatars
            $request->avatar->storeAs('avatars', $filename, 'public');

            // ✅ Gán đúng: chỉ lưu 'avatars/filename.jpg'
            $user->avatar = 'avatars/' . $filename;
        }

        $user->save();
        Toastr::success('Cập nhật hồ sơ thành công!', 'Thành công');
        return back();
    }


    // Hiển thị form đổi mật khẩu
    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    // Xử lý đổi mật khẩu
    public function changePassword(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào thủ công
        if (empty($request->current_password)) {
            Toastr::error('Vui lòng nhập mật khẩu hiện tại.', 'Lỗi');
            return back();
        }

        if (empty($request->password)) {
            Toastr::error('Vui lòng nhập mật khẩu mới.', 'Lỗi');
            return back();
        }

        if (strlen($request->password) < 8) {
            Toastr::error('Mật khẩu mới phải có ít nhất 8 ký tự.', 'Lỗi');
            return back();
        }

        if ($request->password !== $request->password_confirmation) {
            Toastr::error('Xác nhận mật khẩu mới không khớp.', 'Lỗi');
            return back();
        }

        if ($request->current_password === $request->password) {
            Toastr::error('Mật khẩu mới phải khác mật khẩu hiện tại.', 'Lỗi');
            return back();
        }

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            Toastr::error('Mật khẩu hiện tại không đúng.', 'Lỗi');
            return back();
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->password);
        $user->save();

        Toastr::success('Đổi mật khẩu thành công!', 'Thành công');
        return back();
    }
}
