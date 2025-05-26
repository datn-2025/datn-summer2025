@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="left-section">
        <div class="content-wrapper">
            <h1>Xin chào!</h1>
            <p>Nhập thông tin của bạn và bắt đầu hành trình với chúng tôi</p>
            <a href="{{ route('account.register') }}" class="btn btn-outline">ĐĂNG KÝ</a>
        </div>
    </div>
    <div class="right-section">
        <div class="form-container">
            <h2>Đăng nhập</h2>
            <div class="social-buttons">
                <a href="#" class="social-button google">
                    <i class="fab fa-google"></i>
                </a>
            </div>
            <div class="divider">hoặc sử dụng tài khoản của bạn</div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('account.login.submit') }}">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required autofocus value="{{ old('email') }}" />
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required />
                </div>
                <a href="{{ route('account.password.request') }}" class="forgot-password">Quên mật khẩu?</a>
                <button type="submit" class="btn btn-primary">ĐĂNG NHẬP</button>
            </form>
</div>
<!-- Bootstrap CSS & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<!-- Bootstrap CSS & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

<style>
    body {
        margin: 0;
        font-family: 'Montserrat', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(to right, #2193b0, #6dd5ed);
    }

    .auth-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
        position: relative;
        overflow: hidden;
        width: 768px;
        max-width: 100%;
        min-height: 480px;
        display: flex;
    }

    .left-section {
        background: linear-gradient(to right, #2193b0, #6dd5ed);
        color: #fff;
        width: 40%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 40px;
    }

    .content-wrapper {
        max-width: 300px;
    }

    .left-section h1 {
        font-weight: bold;
        margin-bottom: 20px;
    }

    .left-section p {
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .right-section {
        width: 60%;
        background: #fff;
        display: flex;
        align-items: center;
    }

    .form-container {
        padding: 40px;
        width: 100%;
    }

    .form-container h2 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .social-buttons {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
        gap: 10px;
    }

    .social-button {
        border: 1px solid #ddd;
        border-radius: 50%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        margin: 0 5px;
        height: 40px;
        width: 40px;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-button:hover {
        color: #fff;
    }

    .social-button.facebook:hover {
        background: #3b5998;
        border-color: #3b5998;
    }

    .social-button.google:hover {
        background: #db4437;
        border-color: #db4437;
    }

    .social-button.linkedin:hover {
        background: #0077b5;
        border-color: #0077b5;
    }

    .divider {
        text-align: center;
        margin: 20px 0;
        position: relative;
        color: #777;
        font-size: 14px;
    }

    .divider::before,
    .divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 45%;
        height: 1px;
        background: #ddd;
    }

    .divider::before {
        left: 0;
    }

    .divider::after {
        right: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        background: #eee;
        border: none;
        padding: 12px 15px;
        width: 100%;
        margin-bottom: 15px;
    }

    .form-control:focus {
        outline: none;
        background: #fff;
        box-shadow: 0 0 5px rgba(255,75,43,0.2);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid #fff;
        color: #fff;
        padding: 12px 45px;
        border-radius: 20px;
        text-transform: uppercase;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-outline:hover {
        background: #fff;
        color: #2193b0;
    }

    .btn-primary {
        background: linear-gradient(to right, #2193b0, #6dd5ed);
        border: none;
        color: #fff;
        padding: 12px 45px;
        border-radius: 20px;
        text-transform: uppercase;
        font-weight: bold;
        width: 100%;
        margin-top: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #6dd5ed, #2193b0);
        transform: translateY(-2px);
    }

    .forgot-password {
        text-decoration: none;
        color: #777;
        font-size: 14px;
        display: block;
        text-align: center;
        margin: 15px 0;
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: #2193b0;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;

    function nextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }

    setInterval(nextSlide, 3000); // Chuyển ảnh mỗi 3 giây
});
</script>
@endsection