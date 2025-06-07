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

    <section class="w-full bg-cover bg-center bg-no-repeat py-40"
        style="background-image: url('{{asset('storage/images/banner-image-bg-1.jpg')}}')">
        <div class="grid grid-cols-1 md:grid-cols-2 items-center px-6 md:px-10 gap-10 max-w-screen-xl mx-auto">
            <!-- Ảnh sách bên trái -->
            <div class="flex  justify-center">
                <img src="{{asset('storage/images/banner-image3.png')}}" class="h-full object-contain" alt="">
            </div>
            <!-- Nội dung bên phải -->
            <div class="text-center md:text-left space-y-4 text-black">
                <h2 class="text-5xl md:text-6xl font-semibold leading-tight">
                    Giờ hiện tại
                </h2>
                <p class="text-xl md:text-2xl">Một hành trình tri thức đang chờ bạn khám phá!</p>

                <!-- Hiển thị thời gian -->
                <div class="text-5xl font-bold" id="clock-time">00:00:00</div>
                <div class="text-lg mt-1" id="clock-date">Thứ..., 00/00/0000</div>

                <a href="#"
                    class="inline-block bg-red-400 text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-red-600 transition duration-300">
                    Mua ngay
                </a>
            </div>


        </div>


    </section>


    <section class="bg-white py-16">
        <div class="max-w-screen-xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Featured -->
            <div>
                <h3 class="text-xl font-bold mb-4">Nổi bật</h3>
                <div class="flex flex-col gap-y-6">
                    @foreach($featuredBooks as $book)
                        <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                            class="min-h-[300px] flex flex-col justify-between border border-gray-200 rounded p-3 hover:border-black transition">
                            <img src="{{ $book->images->first() ? asset('storage/' . $book->images->first()->image_url) : asset('storage/default.jpg') }}"
                                alt="{{ $book->title }}" class="w-20 h-28 object-cover mb-2">
                            <p class="font-semibold text-sm leading-tight">Tiêu đề: {{ $book->title }}</p>
                            <p class="text-xs text-gray-500">Tác giả: {{ $book->author->name ?? 'Không rõ' }}</p>
                            <p class="text-red-500 font-bold">
                                Giá tiền: {{ number_format($book->formats->first()->price ?? 0, 0, ',', '.') }}₫
                            </p>
                        </div>
                    @endforeach
                </div>

            </div>

            <!-- Mới nhất -->
            <div>
                <h3 class="text-xl font-bold mb-4">Mới nhất</h3>
                <div class="flex flex-col gap-y-6">
                    @foreach($latestBooks as $book)
                        <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                            class="min-h-[300px] flex flex-col justify-between border border-gray-200 rounded p-3 hover:border-black transition">
                            <img src="{{ asset('storage/' . ($book->images->first()->image_url ?? 'default.jpg')) }}"
                                alt="{{ $book->title }}" class="w-20 h-28 object-cover mb-2">
                            <p class="font-semibold text-sm leading-tight">Tiêu đề: {{ $book->title }}</p>
                            <p class="text-xs text-gray-500">Tác giả: {{ $book->author->name ?? 'Không rõ' }}</p>
                            <p class="text-red-500 font-bold">
                                Giá tiền {{ number_format($book->formats->first()->price ?? 0, 0, ',', '.') }}₫
                            </p>
                        </div>
                    @endforeach
                </div>

            </div>

            <!-- Đánh giá cao -->
            <div>
                <h3 class="text-xl font-bold mb-4">Đánh giá cao</h3>
                <div class="flex flex-col gap-y-6">
                    @foreach($bestReviewedBooks as $book)
                        @php
                            $rating = round($book->reviews->avg('rating'), 1);

                        @endphp
                        <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                            class="min-h-[300px] flex flex-col justify-between border border-gray-200 rounded p-3 hover:border-black transition">
                            <img src="{{ asset('storage/' . ($book->images->first()->image_url ?? 'default.jpg')) }}"
                                alt="{{ $book->title }}" class="w-20 h-28 object-cover mb-2">
                            <p class="font-semibold text-sm leading-tight">Tiêu đề: {{ $book->title }}</p>
                            <p class="text-xs text-gray-500">Tác giả: {{ $book->author->name ?? 'Không rõ' }}</p>
                            <p class="text-red-500 font-bold">
                                Giá tiền: {{ number_format($book->formats->first()->price ?? 0, 0, ',', '.') }}₫
                            </p>
                        </div>
                    @endforeach
                </div>

            </div>

            <!-- Giảm giá -->
            <div>
                <h3 class="text-xl font-bold mb-4">Giảm giá</h3>
                <div class="flex flex-col gap-y-6">
                    @foreach($saleBooks as $book)
                        @php
                            $fomat = $book->formats->first();
                            $oldPrice = $fomat->price ?? 0;
                            $discount = $fomat->discount ?? 0;
                            $newPrice = $oldPrice - ($oldPrice * ($discount / 100));
                        @endphp
                        <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                            class="min-h-[300px] flex flex-col justify-between border border-gray-200 rounded p-3 hover:border-black transition">
                            <img src="{{ asset('storage/' . ($book->images->first()->image_url ?? 'default.jpg')) }}"
                                alt="{{ $book->title }}" class="w-20 h-28 object-cover mb-2">
                            <p class="font-semibold text-sm leading-tight">Tiêu đề: {{ $book->title }}</p>
                            <p class="text-xs text-gray-500">Tác giả: {{ $book->author->name ?? 'Không rõ' }}</p>
                            {{-- 💸 Giá có giảm --}}
                            <div class="text-sm mt-1">
                                <span class="line-through text-gray-700 mr-2">
                                    Giá tiền: {{ number_format($oldPrice, 0, ',', '.') }}₫
                                </span>
                                <span class="text-red-600 font-bold">
                                    Giá tiền: {{ number_format($newPrice, 0, ',', '.') }}₫
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </section>





    <section class="px-4 py-10 max-w-screen-xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold uppercase mb-6">🆕 Sản phẩm mới nhất</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 items-stretch">
            @forelse($books as $book)
                <div onclick="window.location='{{ route('books.show', ['slug' => $book->slug]) }}'"
                    class="bg-white rounded shadow-sm overflow-hidden transition-all duration-200 hover:border hover:border-black group flex flex-col h-full">
                    <div class="relative aspect-[1/1.05] bg-gray-100 overflow-hidden">
                        <img src="{{asset('storage/images/' . $book->image)}}"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                            alt="{{$book->title}}">
                        <div class="absolute top-2 right-2 z-10">
                            <i class="far fa-heart text-2xl text-gray-700 hover:text-red-500 cursor-pointer"></i>
                        </div>
                    </div>
                    <div class="p-4 bg-white flex flex-col flex-1 justify-between h-[180px]">
                        <h3 class="text-base font-semibold text-gray-800">{{$book->title}}</h3>
                        <p class="text-sm text-gray-500">{{$book->author?->name ?? 'Không rõ'}}</p>
                        <p class="text-lg font-bold text-red-600 mt-2">{{number_format($book->price, 0, ',', '.')}}₫ </p>
                        <a href="#"
                            class="mt-4 inline-block bg-black text-white px-4 py-2 rounded text-sm hover:bg-gray-800 text-center w-full">
                            Thêm vào giỏ hàng →
                        </a>
                    </div>

                </div>
            @empty
                <p class="col-span-4 text-center text-gray-500">Chưa có sản phẩm nào.</p>
            @endforelse
        </div>
    </section>



    <section class="py-20 bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('storage/images/banner-image-bg.jpg') }}')">
        <h2 class="text-center text-3xl md:text-4xl font-bold mb-10 text-gray-800">Khách hàng nói gì?</h2>

        <div class="swiper reviewSwiper max-w-4xl mx-auto px-4">
            <div class="swiper-wrapper">
                @foreach ($reviews as $review)
                    <div
                        class="swiper-slide bg-white p-6 rounded shadow text-center flex flex-col justify-between min-h-[340px]">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Đánh giá cho sách: <span
                                    class="font-medium text-gray-700">{{ $review->book->title ?? 'Không xác định' }}</span>
                            </p>
                            <p class="text-xs text-gray-400 mb-2">
                                Ngày: {{ $review->created_at }}
                            </p>

                            <p class="text-gray-700 italic mb-4">“{{ $review->comment ?? 'Không có nội dung.' }}”</p>
                            <div class="mb-2 text-yellow-400">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    ★
                                @endfor
                            </div>
                            <p class="text-black font-semibold">
                                {{ $review->user->name ?? 'Ẩn danh' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <br><br>
            <div class="swiper-pagination mt-6"></div>
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




@endsection