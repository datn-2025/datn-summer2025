<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark"
      data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Đăng nhập | BookBee Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    {{-- Styles --}}
    @foreach (['bootstrap.min.css', 'icons.min.css', 'app.min.css', 'custom.min.css'] as $css)
        <link href="{{ asset("assets/css/$css") }}" rel="stylesheet">
    @endforeach
    <script src="{{ asset('assets/js/layout.js') }}"></script>
</head>

<body>
<div class="auth-page-wrapper pt-5">
    <!-- Background -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
                <path d="M0,36 C144,53.6 432,123.2 720,124 C1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- Auth content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="text-center mt-sm-5 mb-4 text-white-50">
                <a href="{{ route('admin.dashboard') }}" class="d-inline-block auth-logo">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo" height="40">
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Chào mừng trở lại!</h5>
                                <p class="text-muted">Đăng nhập để tiếp tục với BookBee Admin</p>
                            </div>

                            <div class="p-2 mt-4">
                                {{-- Thông báo --}}
                                @foreach (['danger' => 'error', 'success' => 'success'] as $type => $msg)
                                    @if (session($msg))
                                        <div class="alert alert-{{ $type }}" role="alert">
                                            {{ session($msg) }}
                                        </div>
                                    @endif
                                @endforeach

                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">
                                        {!! implode('<br>', $errors->all()) !!}
                                    </div>
                                @endif

                                {{-- Form login --}}
                                <form method="POST" action="{{ route('admin.login.submit') }}" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email"
                                               name="email" id="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}"
                                               placeholder="Nhập email" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="float-end">
                                            <a href="#" class="text-muted">Quên mật khẩu?</a>
                                        </div>
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password"
                                                   name="password" id="password"
                                                   class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                                   placeholder="Nhập mật khẩu" required minlength="6">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-muted password-addon"
                                                    type="button" id="password-addon">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="remember"
                                               id="remember"
                                               {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Nhớ tài khoản</label>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="ri-login-box-line align-middle me-1"></i> Đăng nhập
                                        </button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <h5 class="fs-13 mb-4">Đăng nhập bằng</h5>
                                        <div>
                                            <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-google-fill fs-16"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0 text-muted">
                @ <script>document.write(new Date().getFullYear())</script> BookBee Admin Dashboard
                <i class="mdi mdi-heart text-danger"></i>
            </p>
        </div>
    </footer>

</div>

<!-- Scripts -->
@foreach ([
    'libs/bootstrap/js/bootstrap.bundle.min.js',
    'libs/simplebar/simplebar.min.js',
    'libs/node-waves/waves.min.js',
    'libs/feather-icons/feather.min.js',
    'js/pages/plugins/lord-icon-2.1.0.js',
    'js/plugins.js',
    'libs/particles.js/particles.js',
    'js/pages/particles.app.js',
    'js/pages/password-addon.init.js'
] as $js)
    <script src="{{ asset("assets/$js") }}"></script>
@endforeach
</body>
</html>
