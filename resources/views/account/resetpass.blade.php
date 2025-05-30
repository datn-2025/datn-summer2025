@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="left-section">
        <div class="content-wrapper">
            <h1>Quên mật khẩu?</h1>
            <p>Nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>
            <a href="{{ route('account.login') }}" class="btn btn-outline">ĐĂNG NHẬP</a>
        </div>
    </div>
    <div class="right-section">
        <div class="form-container">
            <h2>Đặt lại mật khẩu</h2>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('account.password.email') }}">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required autofocus value="{{ old('email') }}" />
                </div>
                <button type="submit" class="btn btn-primary">GỬI LIÊN KẾT ĐẶT LẠI MẬT KHẨU</button>
            </form>
        </div>
    </div>
</div>

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

    .alert {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 5px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert ul {
        margin: 0;
        padding-left: 20px;
    }
</style>
@endsection