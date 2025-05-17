@extends('layouts.app')
@section('title', 'addidas-vietnam')

@section('content')
    <section class="relative w-full">
        <div class="swiper myHeroSwpier h-[550px]">
            <div class="swiper-wrapper">
                {{-- Slide 1 --}}
                <div class="swiper-slide">
                    <div class="grid grid-cols-3 h-full">
                        <img src="{{asset('storage/images/hero1.jpg')}}" class="w-full h-full object-cover" alt="">
                        <img src="{{asset('storage/images/hero2.jpg')}}" class="w-full h-full object-cover" alt="">
                        <img src="{{asset('storage/images/hero3.jpg')}}" class="w-full h-full object-cover" alt="">
                    </div>
                </div>
                {{-- Slide 2 --}}
                <div class="swiper-slide">
                    <div class="grid grid-cols-3 h-full">
                        <img src="{{asset('storage/images/hot1.jpg')}}" class="w-full h-full object-cover" alt="">
                        <img src="{{asset('storage/images/hot2.jpg')}}" class="w-full h-full object-cover" alt="">
                        <img src="{{asset('storage/images/hot3.jpg')}}" class="w-full h-full object-cover" alt="">
                    </div>
                </div>
            </div>
        </div>
        {{-- Overlay text --}}
        <div class="absolute left-10 bottom-10 text-white max-w-xl z-10 space-y-3">
            <h2 class="inline-block bg-white text-black px-3 py-1 text-2xl font-extrabold uppercase tracking-widest">
                Taekwondo</h2>
            <p class="inline-block bg-white text-black px-3 py-1 text-lg">Sự trở lại bùng nổ: Tái hiện tinh thần thể thao
                qua BST Taekwondo tâm điểm đường phố!</p>

            {{-- Nút hiệu ứng viền lệch --}}
            <a href="#"
                class="group relative mt-4 inline-block px-6 py-2 font-bold text-black bg-white uppercase text-sm transition duration-300">
                {{-- Viền trắng lệch phía sau --}}
                <span
                    class="absolute inset-0 translate-x-[4px] translate-y-[4px] border border-white group-hover:border-gray-800 transition duration-300 z-[-1]"></span>
                {{-- Nền hover xám nhẹ --}}
                <span
                    class="absolute inset-0 bg-gray-100 opacity-0 group-hover:opacity-100 transition duration-300 z-0"></span>
                {{-- Nội dung --}}
                <span class="relative z-10 flex items-center gap-2">Mua ngay →</span>

            </a>
        </div>
    </section>




    <section class="px-4 py-6 max-w-screen-xl mx-auto">
        {{-- Tabs lựa chọn bộ sưu tập --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex gap-2 flex-wrap text-sm font-medium uppercase">
                @foreach ($categories as $index => $category)
                    <button
                        class="tab-button px-4 py-1 border border-black {{$index === 0 ? 'bg-black text-white' : 'hover:bg-gray-100'}} cursor-pointer"
                        data-tab="tab-{{$category->id}}">
                        {{$category->name}}
                    </button>

                @endforeach
            </div>
            <a href="#" class="text-sm font-semibold uppercase border-b border-black hover:opacity-70">Xem tất cả</a>
        </div>
        {{-- Nội dung từng tab --}}
        @foreach ($categories as $index => $category)
            <div id="tab-tab-{{$category->id}}" class="tab-content {{ $index === 0 ? 'block' : 'hidden'}} relative">
                <div class="swiper categorySwiper" id="swiper-{{ $category->id}}">
                    <div class="swiper-wrapper">
                        @foreach ($category->products as $product)
                            <div class="swiper-slide pb-6">
                                <div
                                    class="group bg-white border border-transparent hover:border-black rounded transition duration-300 overflow-hidden flex flex-col h-full">
                                    <div class="relative aspect-[1/1.05] bg-gray-100 overflow-hidden">
                                        <img src="{{asset('storage/images/' . $product->image)}}" alt="{{$product->name}}">
                                        <div class="absolute top-2 right-2 z-10">
                                            <i class="far fa-heart text-2xl text-gray-700 hover:text-red-500 cursor-pointer"></i>
                                        </div>
                                    </div>
                                    <div class="p-4 flex-1">
                                        <p class="text-black font-bold text-[15px]">
                                            {{number_format($product->price, 0, ',', '.')}}₫
                                        </p>
                                        <h3 class="text-sm font-semibold mt-1">{{$product->name}}</h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{$category->name ?? 'Chưa có danh mục'}}
                                        </p>
                                        <a href="#"
                                            class="mt-4 inline-block bg-black text-white px-4 py-2 rounded text-sm hover:bg-gray-800">
                                            Mua ngay →
                                        </a>
                                    </div>

                                </div>
                            </div>

                        @endforeach
                    </div>
                    <div class="swiper-scrollbar mt-4 h-[4px] bg-gray-200 rounded overflow-hidden"
                        id="scrollbar-{{$category->id}}"></div>
                </div>
                {{-- Nút điều hướng --}}
                <div class="swiper-prev absolute -left-3 top-1/2 -translate-y-1/2 z-10 cursor-pointer"
                    id="prev-{{$category->id}}">
                    <i class="fas fa-chevron-left text-xl text-black bg-white rounded-full shadow p-2 hover:bg-gray-200"></i>
                </div>
                <div class="swiper-next absolute -right-3 top-1/2 -translate-y-1/2 z-10 cursor-pointer"
                    id="next-{{$category->id}}">
                    <i class="fas fa-chevron-right text-xl text-black bg-white rounded-full shadow p-2 hover:bg-gray-200"></i>
                </div>
            </div>
   
        @endforeach
    </section>


    <section class="relative w-full mt-6">
        <img src="{{asset('storage/images/lookbook.jpg')}}" alt='lookbook'
            class="w-full h-[550px] object-cover brightness-90">
        <div class="absolute left-10 bottom-10 text-white max-w-xl z-10 space-y-3">
            <h2 class="inline-block bg-white text-black px-3 py-1 text-2xl font-extrabold uppercase tracking-widest">
                Tăng nhiệt độ lên</h2>
            <p class="inline-block bg-white text-black px-3 py-1 text-lg">Bộ sưu tập mùa hè mới của adidas x Mercedes-AMG
                PETRONAS F1.</p>

            {{-- Nút hiệu ứng viền lệch --}}
            <a href="#"
                class="group relative inline-block px-6 py-2 font-bold text-black bg-white uppercase text-sm transition duration-300">
                {{-- Viền trắng lệch phía sau --}}
                <span
                    class="absolute inset-0 translate-x-[4px] translate-y-[4px] border border-white group-hover:border-gray-800 transition duration-300 z-[-1]"></span>
                {{-- Nền hover xám nhẹ --}}
                <span
                    class="absolute inset-0 bg-gray-100 opacity-0 group-hover:opacity-100 transition duration-300 z-0"></span>
                {{-- Nội dung --}}
                <span class="relative z-10 flex items-center gap-2">Mua ngay →</span>

            </a>
    </section>



    <section class="px-4 py-10 max-w-screen-xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold uppercase mb-6">🆕 Sản phẩm mới nhất</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div
                    class="bg-white rounded shadow-sm overflow-hidden transition-all duration-200 hover:border hover:border-black group">
                    <div class="relative aspect-[1/1.05] bg-gray-100 overflow-hidden">
                        <img src="{{asset('storage/images/' . $product->image)}}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                            alt="{{$product->name}}">
                        <div class="absolute top-2 right-2 z-10">
                            <i class="far fa-heart text-2xl text-gray-700 hover:text-red-500 cursor-pointer"></i>
                        </div>
                    </div>
                    <div class="p-4 bg-white">
                        <h3 class="text-base font-semibold text-gray-800">{{$product->name}}</h3>
                        <p class="text-sm text-gray-500">{{$product->category?->name ?? 'Chưa có danh mục'}}</p>
                        <p class="text-lg font-bold text-red-600 mt-2">{{number_format($product->price, 0, ',', '.')}}₫ </p>
                        <a href="#" class="mt-4 inline-block bg-black text-white px-4 py-2 rounded text-sm hover:bg-gray-800">
                            Mua ngay →
                        </a>
                    </div>

                </div>
            @empty
                <p class="col-span-4 text-center text-gray-500">Chưa có sản phẩm nào.</p>
            @endforelse
        </div>
    </section>



    <section class="w-full mt-10">
        <img src="{{asset('storage/images/focus-banner.jpg')}}" alt="Focus Banner"
            class="w-full h-[500px] object-cover md:h-[600px]">
    </section>



    <section class="px-4 py-10 max-w-screen-xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold uppercase mb-6">Hãy Thể Hiện Phong Cách Của Bạn</h2>
        <div class="relative">
            <div class="swiper brandCollabSwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab1.jpg')}}" alt="Collab 1" class="w-full h-[300px] object-cover">
                    </div>
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab2.jpg')}}" alt="Collab 2" class="w-full h-[300px] object-cover">
                    </div>
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab3.jpg')}}" alt="Collab 3" class="w-full h-[300px] object-cover">
                    </div>
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab1.jpg')}}" alt="Collab 1" class="w-full h-[300px] object-cover">
                    </div>
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab2.jpg')}}" alt="Collab 2" class="w-full h-[300px] object-cover">
                    </div>
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab3.jpg')}}" alt="Collab 3" class="w-full h-[300px] object-cover">
                    </div>
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab1.jpg')}}" alt="Collab 1" class="w-full h-[300px] object-cover">
                    </div>
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab2.jpg')}}" alt="Collab 2" class="w-full h-[300px] object-cover">
                    </div>
                    <div class="swiper-slide rounded overflow-hidden shadow hover:shadow-xl transition">
                        <img src="{{asset('storage/images/collab3.jpg')}}" alt="Collab 3" class="w-full h-[300px] object-cover">
                    </div>
                </div>
                {{-- 🔹 Thanh tiến trình --}}
                <div class="brand-collab-pagination mt-4 flex justify-center gap-2"></div>
            </div>
            {{-- 🔹 Nút điều hướng --}}
            <div class="brand-collab-prev absolute -left-3 top-1/2 -translate-y-1/2 z-10 cursor-pointer">
                <i class="fas fa-chevron-left text-xl text-black bg-white rounded-full shadow p-2 hover:bg-gray-200"></i>
            </div>
            <div class="brand-collab-next absolute -right-3 top-1/2 -translate-y-1/2 z-10 cursor-pointer">
                <i class="fas fa-chevron-right text-xl text-black bg-white rounded-full shadow p-2 hover:bg-gray-200"></i>
            </div>
        </div>
    </section>



    <section class="bg-black text-white px-6 py-12 text-sm leading-relaxed">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl font-extrabold uppercase mb-6">Cửa hàng thể thao Adidas – Hiệu năng, phong cách & Đổi mới từ năm 1949</h2>
            <p class="mb-5">Thể thao nâng cao sức khoẻ. Giúp bạn luôn tỉnh táo. Kết nối chúng ta.</p>
            <p class="mb-5">Tìm kiếm trang phục thể thao hiệu suất cao được ứng dụng công nghệ tiên tiến nhất...</p>
            <p class="mb-5">Khám phá cửa hàng trực tuyến của adidas để cập nhật những bộ sưu tập mới nhất...</p>
            <p class="mb-5">Cửa hàng thể thao adidas không chỉ là nơi mua sắm—đây còn là không gian dành cho sự đổi mới và nguồn cảm hứng.</p>
        </div>
    </section>

@endsection