@extends('layouts.app')

@section('content')
<div class="page-center">
    <div class="auth-container">
        <div class="left-section">
            <div class="content-wrapper">
                <h1>Quên mật khẩu?</h1>
                <p>Nhập email của bạn để nhận liên kết đặt lại mật khẩu</p>
                <a href="{{ route('login') }}" class="btn btn-outline">ĐĂNG NHẬP</a>
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

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required value="{{ old('email') }}" />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">GỬI LIÊN KẾT ĐẶT LẠI MẬT KHẨU</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        margin: 0;
        font-family: 'Montserrat', sans-serif;
        min-height: 100vh;
        background: linear-gradient(to right, #2193b0, #6dd5ed);
    }

    .page-center {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .auth-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
        width: 768px;
        max-width: 100%;
        min-height: 400px;
        display: flex;
        overflow: hidden;
    }

    .left-section {
        background: linear-gradient(to right, #2193b0, #6dd5ed);
        color: #fff;
        width: 40%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        text-align: center;
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
    }

    .form-control:focus {
        background: #fff;
        outline: none;
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
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(to right, #6dd5ed, #2193b0);
        transform: translateY(-2px);
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
</style>
@endpush
