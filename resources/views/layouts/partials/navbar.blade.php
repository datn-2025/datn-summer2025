<nav class="bg-white px-6 py-3 shadow-sm border-b">
    <div class="max-w-screen-xl mx-auto flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        {{-- Trái: Logo --}}
        <div class="flex items-center justify-between md:justify-start gap-3">
            <a href="#">
                <img src="{{ asset('storage/images/adidas-logo.png') }}" alt="Logo" class="h-10 w-auto max-w-[120px]">
            </a>
        </div>
        {{-- Giữa: Menu chính --}}
        <ul class="flex flex-wrap justify-center gap-4 text-sm font-bold uppercase tracking-wide text-gray-900">
            <li><a href="#" class="hover:texy-gray-500">giày</a></li>
            <li><a href="#" class="hover:texy-gray-500">nam</a></li>
            <li><a href="#" class="hover:texy-gray-500">nữ</a></li>
            <li><a href="#" class="hover:texy-gray-500">trẻ em</a></li>
            <li><a href="#" class="hover:texy-gray-500">thể thao</a></li>
            <li><a href="#" class="hover:texy-gray-500">các nhãn hiệu</a></li>
            <li><a href="#" class="hover:texy-gray-500">outlet</a></li>
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
                    <input type="text" placeholder="tìm kiếm" class="pl-3 pr-9 py-1 text-sm rounded border border-gray-300 focus:outline-none focus:border-black w-40">
                    <i class="fas fa-search absolute right-2 top-1.5 text-sm text-gray-500"></i>
                </div>
                <i class="far fa-user hover:text-black cursor-pointer"></i>
                <i class="far fa-heart hover:text-black cursor-pointer"></i>
                <div class="relative">
                    <i class="fas fa-shopping-bag hover:text-black cursor-pointer"></i>
                    <span class="absolute -top-2 -right-2 text-[10px] bg-yellow-400 text-black font-bold rounded-full px-1"></span>
                </div>
            </div>
        </div>
        
    </div>
</nav>