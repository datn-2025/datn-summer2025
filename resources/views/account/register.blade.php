@extends('layouts.app')

@section('content')
<div class="container-form">
    <h2 class="mb-4">Tạo tài khoản mới</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('account.register.submit') }}">
        @csrf
        <div class="mb-3 position-relative">
            <i class="fa fa-user"></i>
            <input type="text" name="name" class="form-control" placeholder="Tên đăng nhập" required value="{{ old('name') }}" />
        </div>
        <div class="mb-3 position-relative">
            <i class="fa fa-envelope"></i>
            <input type="email" name="email" class="form-control" placeholder="Email" required value="{{ old('email') }}" />
        </div>
        <div class="mb-3 position-relative">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required />
        </div>
        <div class="mb-3 position-relative">
            <i class="fa fa-lock"></i>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu" required />
        </div>
        <button type="submit" class="btn btn-register w-100 mb-3 text-white">Đăng ký</button>
        <div class="social-container">
            <button type="button" class="social-btn btn-google">
                <i class="fab fa-google"></i> Google
            </button>
        </div>
        <div class="mt-3">
            <a href="{{ route('account.login') }}">Bạn đã có tài khoản? Đăng nhập tại đây</a>
        </div>
    </form>
</div>
@push('styles')
<style>
    body {
        background: url('/images/background1.jpg') no-repeat center center fixed;
        background-size: cover;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f8f9fa;
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(to right, #f5d9e2, #dff1f7);
    }
    .container-form {
        background: rgba(51, 51, 51, 0.75);
        padding: 2rem;
        border-radius: 10px;
        width: 100%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 0 15px rgba(111, 162, 219, 0.7);
    }
    input.form-control {
        padding-left: 45px !important;
        background: transparent;
        border: none;
        border-bottom: 1.5px solid #ced4da;
        color: #f8f9fa;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }
    input.form-control::placeholder {
        color: #adb5bd;
    }
    input.form-control:focus {
        outline: none;
        border-color: #4a90e2;
        box-shadow: none;
        background: transparent;
        color: #f8f9fa;
    }
    .position-relative i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        color: #ced4da;
        pointer-events: none;
        z-index: 2;
    }
    a {
        color: #4a90e2;
        text-decoration: none;
    }
    a:hover {
        color: #357ABD;
        text-decoration: underline;
    }
    .btn-register {
        background-color: transparent;
        border: 2px solid #4a90e2;
        color: #4a90e2;
        width: 100%;
        margin-top: 10px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .btn-register:hover {
        background-color: #357ABD;
        color: white;
    }
    .social-btn {
        flex: 1;
        margin: 0 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        padding: 8px 0;
        transition: background-color 0.3s ease;
    }
    .social-container {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }
    .btn-google {
        background-color: #db4437;
    }
    .btn-google:hover {
        background-color: #a83228;
    }
</style>
@endpush
@endsection