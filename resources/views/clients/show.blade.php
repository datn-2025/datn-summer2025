@extends('layouts.app')
@section('title', $book->title)
@section('content')
    {{-- Breadcrumb --}}
    <div class="bg-gray-50 border-b">
        <nav class="max-w-screen-xl mx-auto px-4 py-3">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="/" class="text-gray-500 hover:text-blue-600">
                        <i class="fas fa-home"></i>
                        <span class="ml-1">Trang chủ</span>
                    </a>
                </li>
                <li class="text-gray-500">/</li>
                <li>
                    <a href="#" class="text-gray-500 hover:text-blue-600">
                        {{ $book->category->name ?? 'Danh mục' }}
                    </a>
                </li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-900 font-medium">{{ $book->title }}</li>
            </ol>
        </nav>
    </div>

    <section class="max-w-screen-xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            {{-- Hình ảnh --}}
            <div class="sticky top-4 space-y-4">
                <div class="relative aspect-square bg-white rounded-lg overflow-hidden border border-gray-200">
                    <img src="{{ asset('storage/' . ($book->images->first()->image_url ?? 'images/default.jpg')) }}"
                         alt="{{ $book->title }}"
                         class="w-full h-full object-contain p-2"
                         id="mainImage">
                </div>

                @if($book->images->count() > 1)
                    <div class="grid grid-cols-5 gap-3">
                        @foreach($book->images as $image)
                            <div class="relative aspect-square bg-white rounded-lg overflow-hidden cursor-pointer hover:ring-2 hover:ring-blue-500 transition-all border border-gray-200">
                                <img src="{{ asset('storage/' . $image->image_url) }}"
                                     alt="{{ $book->title }}"
                                     class="w-full h-full object-contain p-1 thumbnail-image"
                                     onclick="changeMainImage('{{ asset('storage/' . $image->image_url) }}')">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Thông tin sách --}}
            <div class="space-y-4">
                <h1 class="text-3xl font-bold">{{ $book->title }}</h1>
                <p class="text-gray-700">Tác giả: <span class="font-semibold">{{ $book->author->name ?? 'Không rõ' }}</span></p>
                <p class="text-gray-700">Thể loại: <span class="font-semibold">{{ $book->category->name ?? 'Không rõ' }}</span></p>
                <p class="text-gray-700">Thương hiệu: <span class="font-semibold">{{ $book->brand->name ?? 'Không rõ' }}</span></p>
                <p class="text-gray-700">ISBN: {{ $book->isbn }}</p>
                <p class="text-gray-700">Ngày xuất bản: {{ $book->publication_date?->format('d/m/Y') ?? 'Không rõ' }}</p>
                <p class="text-gray-700">Số trang: {{ $book->page_count }}</p>

                @php
                    $formats = $book->formats->sortByDesc(fn($f) => $f->format_name === 'Ebook');
                    $defaultFormat = $formats->first();
                    $defaultPrice = $defaultFormat->price ?? $book->price;
                    $defaultStock = $defaultFormat->stock ?? $book->stock;
                    $discount = $defaultFormat->discount ?? 0;
                    $finalPrice = $defaultPrice - ($defaultPrice * ($discount / 100));
                @endphp

                {{-- Tình trạng & tồn kho --}}
                <p class="text-gray-700">Tình trạng:
                    <span class="font-bold px-3 py-1.5 rounded text-white
                        {{ $defaultStock === -1 ? 'bg-red-500' :
                           ($defaultStock === -2 ? 'bg-yellow-500' :
                           ($defaultStock === 0 ? 'bg-gray-900' :
                           ($defaultFormat?->format_name === 'Ebook' ? 'bg-blue-500' : 'bg-green-500'))) }}"
                          id="bookStock">
                        {{ $defaultFormat?->format_name === 'Ebook' ? 'Có thể mua' :
                            ($defaultStock === -1 ? 'Sắp Ra Mắt' :
                            ($defaultStock === -2 ? 'Ngưng Kinh Doanh' :
                            ($defaultStock === 0 ? 'Hết hàng' : 'Còn hàng'))) }}
                    </span>
                </p>

                <p class="text-gray-700 mt-1">Số lượng tồn kho:
                    <span class="font-semibold" id="productQuantity">
                        {{ $defaultFormat?->format_name === 'Ebook' ? 'Không giới hạn' : ($defaultStock > 0 ? $defaultStock : 0) }}
                    </span>
                </p>

                {{-- Định dạng sách --}}
                @if($book->formats->count())
                    <div class="mt-6">
                        <label for="bookFormatSelect" class="block text-sm font-medium text-gray-700 mb-2">Định dạng sách</label>
                        <select class="w-full border rounded-lg p-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                id="bookFormatSelect">
                            @foreach ($formats as $format)
                                <option value="{{ $format->id }}"
                                        data-price="{{ $format->price }}"
                                        data-stock="{{ $format->stock }}"
                                        data-discount="{{ $format->discount }}"
                                        {{ $format->id === $defaultFormat->id ? 'selected' : '' }}>
                                    {{ $format->format_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Thuộc tính --}}
                @if($book->attributeValues->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        @foreach($book->attributeValues->unique('attribute_id') as $attrVal)
                            <div class="col-span-1">
                                <label for="attribute_{{ $attrVal->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $attrVal->attribute->name ?? 'Không rõ' }}
                                </label>
                                @php
                                    $filteredValues = \App\Models\BookAttributeValue::with('attributeValue')
                                        ->where('book_id', $book->id)
                                        ->whereHas('attributeValue', function ($q) use ($attrVal) {
                                            $q->where('attribute_id', $attrVal->attribute_id);
                                        })
                                        ->get();
                                @endphp
                                <select name="attributes[{{ $attrVal->id }}]"
                                        id="attribute_{{ $attrVal->id }}"
                                        class="w-full border rounded-lg p-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($filteredValues as $bookAttrVal)
                                        <option value="{{ $bookAttrVal->attribute_value_id }}"
                                                data-price="{{ $bookAttrVal->extra_price ?? 0 }}">
                                            {{ $bookAttrVal->attributeValue->value ?? 'Không rõ' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Giá tiền --}}
                <div class="mt-4">
                    <div class="flex items-baseline gap-3">
                        <span class="text-gray-700 min-w-[80px]">Giá tiền:</span>
                        <div class="flex items-baseline gap-2">
                            <span id="bookPrice" class="text-3xl font-bold text-red-600" data-base-price="{{ $defaultPrice }}">
                                {{ number_format($finalPrice, 0, ',', '.') }}₫
                            </span>
                            <span class="text-lg line-through text-gray-400" id="originalPrice" style="{{ $discount > 0 ? '' : 'display: none;' }}">
                                {{ number_format($defaultPrice, 0, ',', '.') }}₫
                            </span>
                            <span id="discountText" class="text-sm font-medium text-red-500 bg-red-50 px-2 py-1 rounded" style="{{ $discount > 0 ? '' : 'display: none;' }}">
                                -<span id="discountPercent">{{ $discount }}</span>%
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Số lượng --}}
                <div class="mt-4 flex items-center justify-start space-x-2">
                    <label for="quantity" class="text-sm font-semibold">Số lượng:</label>
                    <div class="flex items-center space-x-2">
                        <button id="decrementBtn" class="bg-gray-300 hover:bg-gray-400 text-xl px-3 py-1 rounded-lg">-</button>
                        <input type="number" id="quantity" value="1" min="1"
                               class="w-16 text-center border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <button id="incrementBtn" class="bg-gray-300 hover:bg-gray-400 text-xl px-3 py-1 rounded-lg">+</button>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800 transition" id="addToCartBtn">
                        Thêm vào giỏ
                    </button>
                </div>
            </div>
        </div>

        {{-- Mô tả --}}
        <div class="mt-16 bg-white/90 shadow-sm border border-gray-200 rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 border-b border-gray-300 pb-2 text-gray-800 flex items-center">
                <i class="fas fa-align-left mr-2 text-red-400"></i>Mô tả sách
            </h2>
            <div id="bookDescription" class="text-gray-700 text-base leading-relaxed"
                 data-full="{{ e($book->description) }}"
                 data-short="{{ Str::limit(strip_tags($book->description), 200, '...') }}">
                {{ Str::limit(strip_tags($book->description), 200, '...') }}
            </div>
            <button id="showMoreBtn" class="text-blue-500 mt-2 text-sm hover:underline">Xem thêm</button>
        </div>

        {{-- Đánh giá --}}
        <div class="mt-16 bg-white/90 shadow-sm border border-gray-200 rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4 border-b pb-2 border-gray-300 text-gray-800 flex items-center">
                <i class="fas fa-star mr-2 text-yellow-400"></i>Đánh giá từ khách hàng
            </h2>

            @forelse($book->reviews as $review)
                <div class="border-b border-gray-300 py-4">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold text-black">
                            <i class="fas fa-user-circle fa-lg text-secondary me-2"></i>
                            {{ $review->user->name ?? 'Ẩn danh' }}
                        </p>
                        <div class="text-yellow-400 text-sm">
                            @for ($i = 0; $i < $review->rating; $i++) ★ @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 mt-2 italic">{{ $review->comment }}</p>
                    <p class="text-end text-muted small mb-0">{{ $review->created_at?->format('H:i d/m/Y') ?? 'Không rõ' }}</p>
                </div>
            @empty
                <p class="text-gray-500 italic">Chưa có đánh giá nào cho sản phẩm này.</p>
            @endforelse
        </div>

        {{-- Sản phẩm liên quan --}}
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($relatedBooks as $related)
                    <div class="group bg-white rounded-lg shadow-sm overflow-hidden transition-all duration-200 hover:border hover:border-black flex flex-col h-full">
                        <div class="relative aspect-[1/1.05] bg-gray-100 overflow-hidden">
                            <img src="{{ asset('storage/' . ($related->images->first()->image_url ?? 'default.jpg')) }}"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                 alt="{{ $related->title }}">
                            <div class="absolute top-2 right-2 z-10">
                                <i class="far fa-heart text-2xl text-gray-700 hover:text-red-500 cursor-pointer"></i>
                            </div>
                        </div>
                        <div class="p-4 bg-white flex flex-col flex-1 justify-between">
                            <h3 class="text-sm font-semibold text-gray-800">{{ $related->title }}</h3>
                            <p class="text-xs text-gray-500">{{ $related->author->name ?? 'Không rõ' }}</p>
                            <p class="text-red-500 font-bold text-sm mt-1">
                                {{ number_format($related->formats->first()->price ?? 0, 0, ',', '.') }}₫
                            </p>
                            <a href="#" class="mt-4 inline-block bg-black text-white px-4 py-2 rounded text-sm hover:bg-gray-800 text-center w-full">
                                Thêm vào giỏ hàng →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function changeMainImage(imageUrl) {
        document.getElementById('mainImage').src = imageUrl;
    }
</script>
@endpush