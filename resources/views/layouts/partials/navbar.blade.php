
<nav class="bg-white px-6 py-5 shadow-sm border-b">
    <div class="max-w-screen-xl mx-auto flex flex-col gap-2 md:flex-row md:items-center md:justify-between min-h-[80px]">
        {{-- Trái: Logo --}}
        <div class="flex items-center justify-between md:justify-start gap-3">
            <a href="#">
                <img src="{{ asset('storage/images/main-logo.png') }}" alt="Logo" class="h-10 w-auto max-w-[120px]">
            </a>
        </div>
        {{-- Giữa: Menu chính --}}
        <ul class="flex flex-wrap justify-center items-center gap-8 text-base semi-bold uppercase tracking-wide text-gray-900">
            <li><a href="/" class="hover:text-red-500 text-red-500">Trang chủ</a></li>
            <li><a href="#" class="hover:text-red-500">Giới thiệu</a></li>
            <li><a href="#" class="hover:text-red-500">Cửa hàng</a></li>
            <li><a href="/news" class="hover:text-red-500">Tin tức</a></li>
            <li><a href="/contact" class="hover:text-red-500">Liên hệ</a></li>
        </ul>
        {{-- Phải: Tìm kiếm + icon + dòng phụ --}}
        <div class="flex flex-col items-end gap-1 text-sm md:text-xs">
            {{-- Dòng phụ --}}
            <div class="flex items-center gap-4">
                
                {{-- Cờ + chọn ngôn ngữ --}}
                <nav class="relative">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('storage/images/vn-flag.png') }}" alt="Vietnam Flag" class="h-4 w-6">
                        {{-- <span class="text-sm font-medium">VietNam</span> --}}
                    </div>
                </nav>

            </div>
            {{-- Dòng icon + tìm kiếm --}}
            <div class="flex items-center gap-3 text-[15px] text-gray-800">
                {{-- Thanh tìm kiếm --}}
                <div class="relative">
                    <input type="text" placeholder="tìm kiếm"
                        class="pl-3 pr-9 py-1 text-sm rounded border border-gray-300 focus:outline-none focus:border-black w-40">
                    <i class="fas fa-search absolute right-2 top-1.5 text-sm text-gray-500"></i>
                </div>
                <!-- <i class="far fa-user hover:text-black cursor-pointer"></i> -->
               <!-- Thay thế phần user icon cũ bằng code này -->
<div style="position: relative; display: inline-block;">
    <a href="{{ auth()->check() ? '#' : route('login') }}" 
       style="text-decoration: none; color: inherit;"
       onmouseover="this.nextElementSibling.style.display='block'"
       onmouseout="this.nextElementSibling.style.display='none'">
        <i class="far fa-user"></i>
        @auth
            <span style="margin-left: 5px;">{{ Auth::user()->name }}</span>
        @endauth
    </a>
    
    <div style="position: absolute; right: 0; background: white; 
                border: 1px solid #ddd; border-radius: 4px; display: none;"
         onmouseover="this.style.display='block'"
         onmouseout="this.style.display='none'">
        @auth
            <a href="{{ route('account.showUser') }}" 
               style="display: block; padding: 8px 15px; color: #333; text-decoration: none;
                      border-bottom: 1px solid #eee; white-space: nowrap;">
                <i class="fas fa-user-circle" style="margin-right: 8px;"></i> Tài khoản
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        style="width: 100%; text-align: left; padding: 8px 15px; 
                               background: none; border: none; color: #333; cursor: pointer;">
                    <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i> Đăng xuất
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" 
               style="display: block; padding: 8px 15px; color: #333; text-decoration: none;
                      border-bottom: 1px solid #eee; white-space: nowrap;">
                <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i> Đăng nhập
            </a>
            <a href="{{ route('account.register') }}" 
               style="display: block; padding: 8px 15px; color: #333; text-decoration: none;">
                <i class="fas fa-user-plus" style="margin-right: 8px;"></i> Đăng ký
            </a>
        @endauth
    </div>
</div>
                <a href="/wishlist"><i class="far fa-heart hover:text-black cursor-pointer"></i></a>
                <div class="relative">
                    <i class="fas fa-shopping-bag hover:text-black cursor-pointer"></i>
                    <span
                        class="absolute -top-2 -right-2 text-[10px] bg-yellow-400 text-black font-bold rounded-full px-1"></span>
                </div>
            </div>
        </div>

    </div>
</nav>