@extends('layouts.app')
@section('title', 'BookBee')

@section('content')
<section class="w-full bg-gradient-to-br from-red-100 via-white to-blue-100 py-32 md:py-40 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-red-300 opacity-20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-300 opacity-20 rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 items-center px-6 md:px-10 gap-10 max-w-screen-xl mx-auto">
        {{-- Left text --}}
        <div class="space-y-6 text-gray-900">
            <h2 class="text-4xl md:text-6xl font-extrabold leading-tight drop-shadow-lg">
                <span class="inline-block bg-gradient-to-r from-red-400 via-pink-400 to-blue-400 bg-clip-text text-transparent">
                    Sách đặc biệt
                </span>
                <br>
                <span class="text-black">Bộ sưu tập sách</span>
            </h2>
            <p class="text-xl md:text-2xl font-medium">
                <span class="inline-block px-3 py-1 bg-yellow-100 rounded-full text-yellow-700 font-semibold mr-2">🔥 Ưu đãi lớn</span>
                Giảm giá đến <span class="text-red-500 font-bold">30%</span>. Mua ngay hôm nay!
            </p>
            <a href="#"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-red-400 to-pink-500 text-white px-10 py-4 rounded-full text-lg font-bold shadow-lg hover:from-black hover:to-gray-800 transition duration-300 w-max">
                <i class="fas fa-bolt animate-pulse"></i>
                Xem ngay
            </a>
        </div>
        {{-- Right image --}}
        <div class="flex justify-center">
            <div class="relative">
                <img src="{{asset('storage/images/banner-image2.png')}}"
                    class="h-80 md:h-96 object-contain rounded-3xl shadow-2xl border-4 border-white"
                    alt="Banner BookBee">
                <span class="absolute -top-6 -left-6 bg-white rounded-full shadow-lg px-4 py-2 text-red-500 font-bold text-lg flex items-center gap-2">
                    <i class="fas fa-star text-yellow-400"></i> Mới nhất
                </span>
            </div>
        </div>
    </div>
</section>


<section class="bg-white py-16">
    <div class="max-w-screen-xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 px-6 text-center">
        <div class="flex flex-col items-center bg-gradient-to-br from-red-200 via-white to-pink-100 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all p-8">
            <div class="bg-red-100 text-red-500 rounded-full p-4 mb-4 text-3xl animate-bounce">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Giao hàng miễn phí</h3>
            <p class="text-gray-600 text-base">Miễn phí vận chuyển cho mọi đơn hàng toàn quốc.</p>
        </div>
        <div class="flex flex-col items-center bg-gradient-to-br from-yellow-200 via-white to-yellow-50 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all p-8">
            <div class="bg-yellow-100 text-yellow-500 rounded-full p-4 mb-4 text-3xl animate-spin-slow">
                <i class="fas fa-certificate"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Cam kết chất lượng</h3>
            <p class="text-gray-600 text-base">Sản phẩm chính hãng, đảm bảo chất lượng 100%.</p>
        </div>
        <div class="flex flex-col items-center bg-gradient-to-br from-pink-200 via-white to-pink-50 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all p-8">
            <div class="bg-pink-100 text-pink-500 rounded-full p-4 mb-4 text-3xl animate-bounce">
                <i class="fas fa-gift"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Ưu đãi mỗi ngày</h3>
            <p class="text-gray-600 text-base">Khuyến mãi hấp dẫn cập nhật liên tục mỗi ngày.</p>
        </div>
        <div class="flex flex-col items-center bg-gradient-to-br from-blue-200 via-white to-blue-50 rounded-2xl shadow-lg hover:scale-105 hover:shadow-2xl transition-all p-8">
            <div class="bg-blue-100 text-blue-500 rounded-full p-4 mb-4 text-3xl animate-pulse">
                <i class="fas fa-lock"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">Thanh toán an toàn</h3>
            <p class="text-gray-600 text-base">Hỗ trợ nhiều hình thức thanh toán bảo mật cao.</p>
        </div>
    </div>
</section>



<section class="px-4 py-16 max-w-screen-xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div class="flex gap-2 flex-wrap text-base font-semibold uppercase">
            @foreach ($categories as $index => $category)
                <button
                    class="tab-button px-6 py-2 rounded-full border-b-2 transition-all duration-200
                        {{$index === 0 ? 'border-black text-black font-bold' : 'border-transparent text-gray-500 hover:text-black hover:border-black'}}"
                    data-tab="tab-{{$category->id}}">
                    {{$category->name}}
                </button>
            @endforeach
        </div>
        <a href="#"
            class="text-base font-semibold uppercase border-b-2 border-black hover:opacity-70 transition">Xem tất cả</a>
    </div>
    @foreach ($categories as $index => $category)
    <div id="tab-tab-{{$category->id}}" class="tab-content {{ $index === 0 ? 'block' : 'hidden'}}">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            @foreach ($category->books as $book)
                @php
                    $format = $book->formats->first();
                    $price = $format->price ?? $book->price;
                    $discount = $format->discount ?? 0;
                    $finalPrice = $discount > 0 ? $price - ($price * $discount / 100) : $price;
                @endphp
                <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                    class="cursor-pointer flex flex-col bg-white group transition-all duration-200">
                    <div class="aspect-[1/1] bg-gray-100 flex items-center justify-center overflow-hidden">
                        @php
    $img = $book->image ?? '';
    $imgPath = public_path('storage/images/' . $img);
@endphp
<img src="{{ ($img && file_exists($imgPath)) ? asset('storage/images/' . $img) : asset('images/product-item1.png') }}"
    alt="{{$book->title}}"
    class="object-contain w-full h-full transition duration-300 group-hover:scale-105 group-hover:brightness-105" />
                    </div>
                    <div class="py-4 px-2 flex flex-col gap-1 text-left">
                        <span class="font-bold text-lg text-black">
                            @if($discount > 0)
                                <span class="text-gray-400 line-through mr-2">{{number_format($price, 0, ',', '.')}}₫</span>
                                <span class="text-red-600">{{number_format($finalPrice, 0, ',', '.')}}₫</span>
                            @else
                                {{number_format($price, 0, ',', '.')}}₫
                            @endif
                        </span>
                        <span class="text-base text-black font-semibold mt-1">{{$book->title}}</span>
                        <span class="text-sm text-gray-500">{{$category->name ?? 'Chưa có danh mục'}}</span>
                        @if($discount > 0)
                            <span class="text-xs text-red-500 font-semibold mt-1">-{{ $discount }}% Giảm giá</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
</section>

<section class="w-full bg-white py-20 md:py-32">
    <div class="grid grid-cols-1 md:grid-cols-2 items-center px-6 md:px-10 gap-16 max-w-screen-xl mx-auto">
        <!-- Ảnh sách bên trái -->
        <div class="flex justify-center">
            <div class="bg-gray-100 p-8 rounded-lg">
                <img src="{{asset('storage/images/banner-image3.png')}}" 
                     class="h-80 md:h-96 object-contain" 
                     alt="Sách đặc biệt">
            </div>
        </div>
        
        <!-- Nội dung bên phải -->
        <div class="text-left space-y-6 text-black">
            <h2 class="text-4xl md:text-5xl font-bold leading-tight">
                Thời gian đọc sách
            </h2>
            <p class="text-lg md:text-xl text-gray-600">
                Mỗi giây trôi qua là một cơ hội để khám phá tri thức mới
            </p>

            <!-- Hiển thị thời gian -->
            <div class="space-y-2">
                <div class="text-3xl md:text-4xl font-mono font-bold text-black" id="clock-time">00:00:00</div>
                <div class="text-base text-gray-500" id="clock-date">Thứ..., 00/00/0000</div>
            </div>

            <a href="#"
                class="inline-block bg-black text-white px-8 py-3 text-sm font-semibold hover:bg-gray-800 transition duration-300 uppercase tracking-wide">
                Mua ngay
            </a>
        </div>
    </div>
</section>


<section class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-black mb-4">KHÁM PHÁ BỘ SƯU TẬP</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Tuyển chọn những cuốn sách hay nhất từ các thể loại khác nhau</p>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Featured Books - Large Card -->
            <div class="lg:col-span-6">
                <div class="bg-black text-white rounded-none overflow-hidden h-full min-h-[600px] relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent z-10"></div>
                    <img src="{{ $featuredBooks->first()?->images->first() ? asset('storage/' . $featuredBooks->first()->images->first()->image_url) : asset('storage/default.jpg') }}" 
                         alt="Featured" 
                         class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity duration-500">
                    
                    <div class="relative z-20 p-8 flex flex-col justify-end h-full">
                        <div class="mb-6">
                            <span class="text-white/80 text-sm font-medium tracking-wider uppercase">NỔI BẬT</span>
                            <h3 class="text-3xl md:text-4xl font-bold mt-2 mb-4 leading-tight">
                                {{ $featuredBooks->first()?->title ?? 'Sách nổi bật' }}
                            </h3>
                            <p class="text-white/90 mb-6">{{ $featuredBooks->first()?->author->name ?? 'Tác giả' }}</p>
                            <p class="text-2xl font-bold text-white">
                                {{ number_format($featuredBooks->first()?->formats->first()?->price ?? 0, 0, ',', '.') }}₫
                            </p>
                        </div>
                        
                        <button onclick="window.location='{{ route('books.show', ['slug' => $featuredBooks->first()?->slug ?? '#']) }}'"
                                class="bg-white text-black px-8 py-4 font-bold text-sm tracking-wider uppercase hover:bg-gray-100 transition-colors duration-300 w-fit">
                            KHÁM PHÁ NGAY
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-6 space-y-8">
                
                <!-- Top Row - Latest & Best Reviewed -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Latest Books -->
                    <div class="bg-white p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-black uppercase tracking-wide">MỚI NHẤT</h3>
                            <div class="w-8 h-0.5 bg-black"></div>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($latestBooks->take(3) as $book)
                                <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                                     class="flex items-center gap-4 p-3 hover:bg-gray-50 transition-colors duration-200 cursor-pointer group">
                                    <img src="{{ asset('storage/' . ($book->images->first()->image_url ?? 'default.jpg')) }}"
                                         alt="{{ $book->title }}" 
                                         class="w-12 h-16 object-cover">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sm text-black group-hover:text-gray-600 transition-colors">
                                            {{ Str::limit($book->title, 40) }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $book->author->name ?? 'Không rõ' }}</p>
                                        <p class="text-sm font-bold text-black mt-1">
                                            {{ number_format($book->formats->first()->price ?? 0, 0, ',', '.') }}₫
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Best Reviewed -->
                    <div class="bg-white p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-black uppercase tracking-wide">ĐÁNH GIÁ CAO</h3>
                            <div class="w-8 h-0.5 bg-black"></div>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($bestReviewedBooks->take(3) as $book)
                                @php
                                    $rating = round($book->reviews->avg('rating'), 1);
                                @endphp
                                <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                                     class="flex items-center gap-4 p-3 hover:bg-gray-50 transition-colors duration-200 cursor-pointer group">
                                    <img src="{{ asset('storage/' . ($book->images->first()->image_url ?? 'default.jpg')) }}"
                                         alt="{{ $book->title }}" 
                                         class="w-12 h-16 object-cover">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sm text-black group-hover:text-gray-600 transition-colors">
                                            {{ Str::limit($book->title, 40) }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $book->author->name ?? 'Không rõ' }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <div class="flex text-yellow-400 text-xs">
                                                @for($i = 0; $i < 5; $i++)
                                                    @if($i < $rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-500">({{ $rating }})</span>
                                        </div>
                                        <p class="text-sm font-bold text-black mt-1">
                                            {{ number_format($book->formats->first()->price ?? 0, 0, ',', '.') }}₫
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Bottom - Sale Books -->
                <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-2xl font-bold uppercase tracking-wide">GIẢM GIÁ ĐẶC BIỆT</h3>
                                <p class="text-white/90 mt-1">Tiết kiệm đến 50% cho sách chọn lọc</p>
                            </div>
                            <div class="text-right">
                                <span class="text-4xl font-bold">50%</span>
                                <p class="text-sm">OFF</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($saleBooks->take(2) as $book)
                                @php
                                    $format = $book->formats->first();
                                    $oldPrice = $format->price ?? 0;
                                    $discount = $format->discount ?? 0;
                                    $newPrice = $oldPrice - ($oldPrice * ($discount / 100));
                                @endphp
                                <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                                     class="flex items-center gap-4 p-4 bg-white/10 backdrop-blur-sm rounded hover:bg-white/20 transition-colors duration-200 cursor-pointer">
                                    <img src="{{ asset('storage/' . ($book->images->first()->image_url ?? 'default.jpg')) }}"
                                         alt="{{ $book->title }}" 
                                         class="w-16 h-20 object-cover">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sm mb-1">{{ Str::limit($book->title, 30) }}</h4>
                                        <p class="text-xs text-white/80 mb-2">{{ $book->author->name ?? 'Không rõ' }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="line-through text-white/60 text-sm">
                                                {{ number_format($oldPrice, 0, ',', '.') }}₫
                                            </span>
                                            <span class="text-white font-bold text-lg">
                                                {{ number_format($newPrice, 0, ',', '.') }}₫
                                            </span>
                                        </div>
                                        @if($discount > 0)
                                            <span class="inline-block bg-white text-red-500 text-xs px-2 py-1 rounded mt-1 font-bold">
                                                -{{ $discount }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="mt-6 bg-white text-red-500 px-8 py-3 font-bold text-sm tracking-wider uppercase hover:bg-gray-100 transition-colors duration-300">
                            XEM TẤT CẢ GIẢM GIÁ
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom CTA -->
        <div class="text-center mt-16">
            <button class="bg-black text-white px-12 py-4 font-bold text-sm tracking-wider uppercase hover:bg-gray-800 transition-colors duration-300">
                XEM TẤT CẢ SẢN PHẨM
            </button>
        </div>
    </div>
</section>





<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-black uppercase tracking-tight mb-2">
                        SẢN PHẨM MỚI NHẤT
                    </h2>
                    <div class="w-20 h-1 bg-black"></div>
                </div>
                <a href="#" 
                   class="group flex items-center gap-2 text-black font-bold text-sm uppercase tracking-wider hover:opacity-70 transition-opacity">
                    Xem tất cả
                    <span class="transform group-hover:translate-x-1 transition-transform">→</span>
                </a>
            </div>
            <p class="text-lg text-gray-600 max-w-xl">
                Khám phá những cuốn sách mới nhất được tuyển chọn đặc biệt cho bạn
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($books as $book)
                <div class="group cursor-pointer" onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'">
                    <!-- Product Image -->
                    <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden mb-4">
                        <img src="{{asset('storage/images/' . $book->image)}}"
                             class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105"
                             alt="{{$book->title}}">
                        
                        <!-- Overlay Actions -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300">
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="bg-white/90 hover:bg-white text-black p-3 rounded-full shadow-lg backdrop-blur-sm transition-all duration-200 hover:scale-110">
                                    <i class="far fa-heart text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Quick Add to Cart -->
                        <div class="absolute bottom-0 left-0 right-0 bg-black text-white py-3 px-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <button class="w-full text-center font-bold text-sm uppercase tracking-wider hover:opacity-80 transition-opacity">
                                Thêm vào giỏ hàng
                            </button>
                        </div>

                        <!-- New Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-black text-white px-3 py-1 text-xs font-bold uppercase tracking-wide">
                                Mới
                            </span>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-2">
                        <h3 class="font-bold text-lg text-black group-hover:opacity-70 transition-opacity line-clamp-2">
                            {{$book->title}}
                        </h3>
                        <p class="text-sm text-gray-500 uppercase tracking-wide">
                            {{$book->author?->name ?? 'Không rõ'}}
                        </p>
                        <div class="flex items-center justify-between">
                            <p class="text-xl font-bold text-black">
                                {{number_format($book->price, 0, ',', '.')}}₫
                            </p>
                            <div class="flex items-center text-yellow-400 text-sm">
                                <span class="mr-1">★</span>
                                <span class="text-gray-500">4.5</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-book text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Chưa có sản phẩm nào</h3>
                    <p class="text-gray-500">Hãy quay lại sau để khám phá những cuốn sách mới nhất</p>
                </div>
            @endforelse
        </div>

        <!-- Load More Button -->
        @if($books->count() >= 8)
            <div class="text-center mt-16">
                <button class="bg-black text-white px-12 py-4 font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition-colors duration-300 group">
                    Xem thêm sản phẩm
                    <span class="ml-2 transform group-hover:translate-x-1 transition-transform">→</span>
                </button>
            </div>
        @endif
    </div>
</section>





// ...existing code...

<section class="py-20 bg-black text-white relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-48 translate-x-48"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full translate-y-36 -translate-x-36"></div>
        <div class="absolute top-1/2 left-1/2 w-2 h-32 bg-white/10 transform -translate-x-1/2 -translate-y-1/2 rotate-45"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <div class="inline-block mb-4">
                <span class="bg-white text-black px-4 py-1 text-xs font-bold uppercase tracking-wider">
                    CUSTOMER FEEDBACK
                </span>
            </div>
            <h2 class="text-4xl md:text-6xl font-bold uppercase tracking-tight mb-6">
                KHÁCH HÀNG NÓI GÌ?
            </h2>
            <div class="w-20 h-1 bg-white mx-auto mb-6"></div>
            <p class="text-xl text-white/80 max-w-2xl mx-auto">
                Trải nghiệm thực tế từ những độc giả đã tin tưởng BookBee
            </p>
        </div>

        <!-- Reviews Swiper -->
        <div class="swiper reviewSwiper">
            <div class="swiper-wrapper pb-12">
                @foreach ($reviews as $review)
                    <div class="swiper-slide">
                        <div class="bg-white text-black p-8 relative group hover:bg-gray-50 transition-all duration-300 min-h-[400px] flex flex-col">
                            <!-- Quote Icon -->
                            <div class="absolute top-6 left-6 text-6xl text-gray-200 font-serif">"</div>
                            
                            <!-- Content -->
                            <div class="relative z-10 flex flex-col h-full pt-8">
                                <!-- Book Info -->
                                <div class="mb-6">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-2 h-2 bg-black"></div>
                                        <span class="text-xs uppercase tracking-wider font-bold text-gray-500">
                                            ĐÁNH GIÁ SẢN PHẨM
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-lg text-black mb-1">
                                        {{ $review->book->title ?? 'Không xác định' }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $review->created_at->format('d/m/Y') }}
                                    </p>
                                </div>

                                <!-- Review Content -->
                                <div class="flex-grow mb-6">
                                    <p class="text-gray-700 leading-relaxed text-lg font-medium">
                                        "{{ $review->comment ?? 'Sản phẩm tuyệt vời, rất hài lòng với chất lượng.' }}"
                                    </p>
                                </div>

                                <!-- Rating -->
                                <div class="mb-6">
                                    <div class="flex items-center gap-1 mb-2">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="text-2xl {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">
                                                ★
                                            </span>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-bold text-black">
                                        {{ $review->rating }}/5 STARS
                                    </span>
                                </div>

                                <!-- Customer Info -->
                                <div class="border-t border-gray-200 pt-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-black text-lg uppercase tracking-wide">
                                                {{ $review->user->name ?? 'Anonymous' }}
                                            </p>
                                            <p class="text-sm text-gray-500 uppercase tracking-wider">
                                                Verified Customer
                                            </p>
                                        </div>
                                        <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center font-bold text-lg">
                                            {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Accent Line -->
                            <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-black to-gray-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Pagination -->
            <div class="swiper-pagination-custom flex justify-center items-center gap-4 mt-12">
                <button class="swiper-prev-custom w-12 h-12 bg-white/10 hover:bg-white/20 border border-white/20 text-white flex items-center justify-center transition-all duration-300 group">
                    <span class="transform group-hover:-translate-x-1 transition-transform">←</span>
                </button>
                
                <div class="swiper-pagination-bullets flex gap-2"></div>
                
                <button class="swiper-next-custom w-12 h-12 bg-white/10 hover:bg-white/20 border border-white/20 text-white flex items-center justify-center transition-all duration-300 group">
                    <span class="transform group-hover:translate-x-1 transition-transform">→</span>
                </button>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20 pt-16 border-t border-white/20">
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold mb-2">4.8</div>
                <div class="text-white/80 uppercase tracking-wider text-sm">Average Rating</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold mb-2">{{ $reviews->count() }}+</div>
                <div class="text-white/80 uppercase tracking-wider text-sm">Customer Reviews</div>
            </div>
            <div class="text-center">
                <div class="text-4xl md:text-5xl font-bold mb-2">98%</div>
                <div class="text-white/80 uppercase tracking-wider text-sm">Satisfaction Rate</div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="text-center mt-16">
            <a href="#" class="inline-flex items-center gap-3 bg-white text-black px-10 py-4 font-bold text-sm uppercase tracking-wider hover:bg-gray-100 transition-all duration-300 group">
                <span>XEM TẤT CẢ ĐÁNH GIÁ</span>
                <span class="transform group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>
    </div>
</section>







    <section class="px-4 py-16 max-w-screen-xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl md:text-3xl font-bold uppercase flex items-center gap-2">📰 Tin tức mới nhất</h2>
            <a href="#"
                class="bg-red-400 text-white px-6 py-2 rounded-full text-sm hover:bg-red-600 transition duration-300">
                Xem tất cả
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($articles as $article)
                <div class="bg-white rounded shadow overflow-hidden hover:shadow-lg transition">
                    <img src="{{asset('storage/' . $article->thumbnail)}}" alt="{{$article->title}}"
                        class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col min-h-[270px]">
                        <div class="flex flex-col flex-grow">
                             <p class=" text-sm text-pink-500 font-medium mb-1">
                            {{ $article->category ?? ' Tin tức' }}
                        </p>
                        <h3 class=" text-lg font-bold mb-2 leading-snug">{{$article->title}}</h3>
                        <p class=" text-sm text-gray-600 mb-4">{{Str::limit($article->summary, 100)}}</p>
                       
                        </div>
                        <div class="mt-4">
                             <a href="#" class=" inline-block text-sm text-red-500 hover:underline font-semibold">
                            Đọc thêm →
                        </a>
                        </div>
                       
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-center text-gray-500">Chưa có bài viết nào.</p>
            @endforelse
        </div>
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reviewSwiper = new Swiper('.reviewSwiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination-bullets',
                    clickable: true,
                    bulletClass: 'swiper-pagination-bullet-custom',
                    bulletActiveClass: 'swiper-pagination-bullet-active-custom',
                    renderBullet: function (index, className) {
                        return '<span class="' + className + ' w-3 h-3 bg-white/30 hover:bg-white/60 transition-all duration-300 cursor-pointer"></span>';
                    },
                },
                navigation: {
                    nextEl: '.swiper-next-custom',
                    prevEl: '.swiper-prev-custom',
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 40,
                    },
                },
                effect: 'slide',
                speed: 600,
            });
        });
        </script>
<style>
    .swiper-pagination-bullet-custom {
        opacity: 1;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .swiper-pagination-bullet-active-custom {
        background: white !important;
        transform: scale(1.2);
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>
@endsection