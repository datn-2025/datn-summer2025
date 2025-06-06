<nav id="header-nav" class="navbar navbar-expand-lg py-3 bg-white border-bottom shadow-sm">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="/">
            <img src="{{ asset('storage/images/main-logo.png') }}" class="logo" alt="Logo" style="height: 40px;">
        </a>

        {{-- Toggle button for mobile --}}
        <button class="navbar-toggler d-flex d-lg-none p-2" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Offcanvas menu --}}
        <div class="offcanvas offcanvas-end" tabindex="-1" id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel">
            <div class="offcanvas-header">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('storage/images/main-logo.png') }}" class="logo" alt="Logo"
                        style="height: 40px;">
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                {{-- Menu --}}
                <ul class="navbar-nav text-uppercase flex-grow-1 justify-content-lg-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link me-4 {{ Request::is('/') ? 'active' : '' }}" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-4 {{ Request::is('books') ? 'active' : '' }}" href="/books">Sản phẩm
                            </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-4 {{ Request::is('shop') ? 'active' : '' }}" href="/shop">Cửa hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-4 {{ Request::is('news') ? 'active' : '' }}" href="/news">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-4 {{ Request::is('contact') ? 'active' : '' }}" href="/contact">Liên
                            hệ</a>
                    </li>

                </ul>

                {{-- Phải: icon, tìm kiếm, user --}}
                <div class="d-flex align-items-center gap-3">
                    {{-- Tìm kiếm --}}
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y pe-2 text-muted"></i>
                    </div>


                    {{-- User dropdown --}}
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none"
                            data-bs-toggle="dropdown">
                            <i class="far fa-user me-1"></i>
                            @auth
                                <span>{{ Auth::user()->name }}</span>
                            @endauth
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            @auth
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.showUser') }}">
                                        <i class="fas fa-user-circle me-2"></i> Tài khoản
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('account.register') }}">
                                        <i class="fas fa-user-plus me-2"></i> Đăng ký
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>

                    {{-- Wishlist --}}
                    <a href="/wishlist" class="text-dark">
                        <i class="far fa-heart"></i>
                    </a>

                    {{-- Cart --}}
                    <a href="/cart" class="position-relative text-dark">
                        <i class="fas fa-shopping-bag"></i>
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark small">0</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
