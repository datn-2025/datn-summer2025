
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
            <li><a href="#" class="hover:text-red-500">Tin tức</a></li>
            <li><a href="#" class="hover:text-red-500">Trang</a></li>
            <li><a href="#" class="hover:text-red-500">Liên hệ</a></li>
            <li><a href="#" class="hover:text-red-500 underline">Nhận Pro</a></li>
        </ul>
        {{-- Phải: Tìm kiếm + icon + dòng phụ --}}
        <div class="flex flex-col items-end gap-1 text-sm md:text-xs">
            {{-- Dòng phụ --}}
            <div class="flex items-center gap-4">
                <a href="#" class="hover:underline whitespace-nowrap">trợ giúp</a>
                <a href="#" class="hover:underline whitespace-nowrap">trình theo dõi đơn hàng</a>
                <a href="#" class="hover:underline whitespace-nowrap">đăng ký hội viên</a>

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
                <i class="far fa-user hover:text-black cursor-pointer"></i>
                <i class="far fa-heart hover:text-black cursor-pointer"></i>
                <div class="relative">
                    <i class="fas fa-shopping-bag hover:text-black cursor-pointer"></i>
                    <span
                        class="absolute -top-2 -right-2 text-[10px] bg-yellow-400 text-black font-bold rounded-full px-1"></span>
                </div>
            </div>
        </div>

    </div>
</nav>