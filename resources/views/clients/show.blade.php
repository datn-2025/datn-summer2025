@extends('layouts.app')
@section('title', $book->title)

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

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
                    <a href="" class="text-gray-500 hover:text-blue-600">
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
            <div class="sticky top-4 space-y-4">
                <!-- Ảnh chính -->
                <div class="relative aspect-square bg-white rounded-lg overflow-hidden border border-gray-200">
                    <img src="{{ asset('storage/' . ($book->images->first()->image_url ?? 'images/default.jpg')) }}"
                        alt="{{ $book->title }}" class="w-full h-full object-contain p-2" id="mainImage">
                </div>

                <!-- Thumbnails -->
                @if ($book->images->count() > 1)
                    <div class="grid grid-cols-5 gap-3">
                        @foreach ($book->images as $image)
                            <div
                                class="relative aspect-square bg-white rounded-lg overflow-hidden cursor-pointer hover:ring-2 hover:ring-blue-500 transition-all border border-gray-200">
                                <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $book->title }}"
                                    class="w-full h-full object-contain p-1 thumbnail-image"
                                    onclick="changeMainImage('{{ asset('storage/' . $image->image_url) }}')">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <h1 class="text-3xl font-bold">{{ $book->title }}</h1>
                <p class="text-gray-700">Tác giả: <span class="font-semibold">{{ $book->author->name ?? 'không rõ' }}</span>
                </p>
                <p class="text-gray-700">Thể loại: <span
                        class="font-semibold">{{ $book->category->name ?? 'không rõ' }}</span></p>
                <p class="text-gray-700">Thương hiệu: <span
                        class="font-semibold">{{ $book->brand->name ?? 'không rõ' }}</span></p>
                <p class="text-gray-700">ISBN: {{ $book->isbn }}</p>
                <p class="text-gray-700">Ngày xuất bản: {{ $book->publication_date->format('d/m/Y') }}</p>
                <p class="text-gray-700">Số trang: {{ $book->page_count }}</p>

                @php
                    $defaultFormat = $book->formats->first();
                    $defaultPrice = $defaultFormat->price ?? $book->price;
                    $defaultStock = $defaultFormat->stock ?? $book->stock;
                    $discount = $defaultFormat->discount ?? 0;
                    $finalPrice = $defaultPrice - ($defaultPrice * ($discount / 100));
                @endphp

                <!-- Tình trạng và tồn kho -->
                <p class="text-gray-700">Tình trạng:
                    <span
                        class="font-bold px-3 py-1.5 rounded text-white
                        {{ $defaultStock === -1
                            ? 'bg-red-500'
                            : ($defaultStock === -2
                                ? 'bg-yellow-500'
                                : ($defaultStock === 0
                                    ? 'bg-gray-900'
                                    : 'bg-green-500')) }}"
                        id="bookStock">
                        {{ $defaultStock === -1
                            ? 'Sắp Ra Mắt'
                            : ($defaultStock === -2
                                ? 'Ngưng Kinh Doanh'
                                : ($defaultStock === 0
                                    ? 'Hết Hàng Tồn Kho'
                                    : 'Còn Hàng')) }}
                    </span>
                </p>
                <div class="mt-2">
                    <p class="text-gray-700">Số lượng tồn kho: <span class="font-semibold"
                            id="productQuantity">{{ $defaultStock > 0 ? $defaultStock : 0 }}</span></p>
                </div><!-- Chọn định dạng -->
                @if ($book->formats->count())
                    <div class="mt-6">
                        <label for="bookFormatSelect" class="block text-sm font-medium text-gray-700 mb-2">Định dạng
                            sách</label>
                        <select
                            class="w-full border rounded-lg p-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="bookFormatSelect">
                            @foreach ($book->formats as $i => $format)
                                <option value="{{ $format->id }}" data-price="{{ $format->price }}"
                                    data-stock="{{ $format->stock }}" data-discount="{{ $format->discount }}"
                                    {{ $i === 0 ? 'selected' : '' }}>
                                    {{ $format->format_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Thuộc tính -->
                @if ($book->attributeValues->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        @foreach ($book->attributeValues->unique('attribute_id') as $attrVal)
                            <div class="col-span-1">
                                <label for="attribute_{{ $attrVal->id }}"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $attrVal->attribute->name ?? 'Không rõ' }}
                                </label>
                                <select name="attributes[{{ $attrVal->id }}]" id="attribute_{{ $attrVal->id }}"
                                    class="w-full border rounded-lg p-2.5 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    onchange="updatePriceAndStock()">
                                    @foreach ($attrVal->attribute->values as $value)
                                        @php
                                            $bookAttrValue = \App\Models\BookAttributeValue::where('book_id', $book->id)
                                                ->where('attribute_value_id', $value->id)
                                                ->first();
                                            $extraPrice = $bookAttrValue ? $bookAttrValue->extra_price : 0;
                                        @endphp
                                        <option value="{{ $value->id }}" data-price="{{ $extraPrice }}">
                                            {{ $value->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                @endif <!-- Giá tiền -->
                <div class="mt-4">
                    <div class="flex items-baseline gap-3">
                        <span class="text-gray-500 min-w-[80px]">Giá tiền:</span>
                        <div class="flex items-baseline gap-2">
                            <span id="bookPrice" class="text-3xl font-bold text-red-600"
                                data-base-price="{{ $defaultPrice }}">
                                {{ number_format($finalPrice, 0, ',', '.') }}₫
                            </span>
                            @if ($discount > 0)
                                <span class="text-lg line-through text-gray-400"
                                    id="originalPrice">{{ number_format($defaultPrice, 0, ',', '.') }}₫</span>
                                <span id="discountText" class="text-sm font-medium text-red-500 bg-red-50 px-2 py-1 rounded"
                                    style="display: {{ $discount > 0 ? 'inline' : 'none' }}">
                                    -<span id="discountPercent">{{ $discount }}</span>%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Số lượng -->
                <div class="mt-4 flex items-center justify-start space-x-2">
                    <label for="quantity" class="text-sm font-semibold">Số lượng:</label>
                    <div class="flex items-center space-x-2">
                        <button id="decrementBtn"
                            class="bg-gray-300 hover:bg-gray-400 text-xl px-3 py-1 rounded-lg transition-all ease-in-out duration-200">-</button>
                        <input type="number" id="quantity" value="1" min="1"
                            class="w-16 text-center border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                        <button id="incrementBtn"
                            class="bg-gray-300 hover:bg-gray-400 text-xl px-3 py-1 rounded-lg transition-all ease-in-out duration-200">+</button>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800 transition" id="addToCartBtn">
                        Thêm vào giỏ hàng 
                    </button>
                </div>
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Chia sẻ sản phẩm:</h3>

                    <div class="flex gap-3 items-center text-xl">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                            target="_blank">
                            <i class="fab fa-facebook-f text-blue-600"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}" target="_blank">
                            <i class="fab fa-twitter text-sky-500"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}"
                            target="_blank">
                            <i class="fab fa-linkedin-in text-blue-700"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode(url()->current()) }}" target="_blank">
                            <i class="fab fa-whatsapp text-green-500"></i>
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}" target="_blank">
                            <i class="fab fa-telegram-plane text-blue-500"></i>
                        </a>
                    </div>
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
                            @for ($i = 0; $i < $review->rating; $i++)
                                ★
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-600 mt-2 italic">{{ $review->comment }}</p>
                    <p class="text-end text-muted small mb-0">{{ $review->created_at->format('H:i d/m/Y') }}</p>
                </div>
            @empty
                <p class=" text-gray-500 italic">Chưa có đánh giá nào cho sản phẩm này.</p>
            @endforelse
        </div>

        {{-- Sản phẩm liên quan --}}
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach ($relatedBooks as $related)
                    <div
                        class="group bg-white rounded-lg shadow-sm overflow-hidden transition-all duration-200 hover:border hover:border-black flex flex-col h-full">

                        <!-- Hình ảnh sản phẩm và icon trái tim -->
                        <div class="relative aspect-[1/1.05] bg-gray-100 overflow-hidden">
                            <img src="{{ asset('storage/' . ($related->images->first()->image_url ?? 'default.jpg')) }}"
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                alt="{{ $related->title }}">

                            <!-- Icon trái tim -->
                            <div class="absolute top-2 right-2 z-10">
                                <i class="far fa-heart text-2xl text-gray-700 hover:text-red-500 cursor-pointer"></i>
                            </div>
                        </div>

                        <!-- Thông tin sản phẩm -->
                        <div class="p-4 bg-white flex flex-col flex-1 justify-between">
                            <h3 class="text-sm font-semibold text-gray-800">{{ $related->title }}</h3>
                            <p class="text-xs text-gray-500">{{ $related->author->name ?? 'Không rõ' }}</p>
                            <p class="text-red-500 font-bold text-sm mt-1">
                                {{ number_format($related->formats->first()->price ?? 0, 0, ',', '.') }}₫
                            </p>
                            <a href="#"
                                class="mt-4 inline-block bg-black text-white px-4 py-2 rounded text-sm hover:bg-gray-800 text-center w-full">
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // Wait for toastr to load
    $(document).ready(function() {
        // Configure toastr options
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        }
    });

    function changeMainImage(imageUrl) {
        document.getElementById('mainImage').src = imageUrl;
    }

    // Update price and stock based on selected format and attributes
    function updatePriceAndStock() {
        const formatSelect = document.getElementById('bookFormatSelect');
        const basePrice = parseFloat(document.getElementById('bookPrice').dataset.basePrice) || 0;
        
        let finalPrice = basePrice;
        let stock = 0;
        let discount = 0;

        // Get format data
        if (formatSelect && formatSelect.selectedOptions[0]) {
            const selectedOption = formatSelect.selectedOptions[0];
            finalPrice = parseFloat(selectedOption.dataset.price) || basePrice;
            stock = parseInt(selectedOption.dataset.stock) || 0;
            discount = parseFloat(selectedOption.dataset.discount) || 0;
        }

        // Add attribute extra costs
        const attributeSelects = document.querySelectorAll('[name^="attributes["]');
        attributeSelects.forEach(select => {
            if (select.selectedOptions[0]) {
                const extraPrice = parseFloat(select.selectedOptions[0].dataset.price) || 0;
                finalPrice += extraPrice;
            }
        });

        // Calculate final price with discount
        const discountAmount = finalPrice * (discount / 100);
        const priceAfterDiscount = finalPrice - discountAmount;

        // Update price display
        document.getElementById('bookPrice').textContent = new Intl.NumberFormat('vi-VN').format(priceAfterDiscount) + '₫';
        
        const originalPriceElement = document.getElementById('originalPrice');
        const discountTextElement = document.getElementById('discountText');
        const discountPercentElement = document.getElementById('discountPercent');

        if (discount > 0) {
            if (originalPriceElement) {
                originalPriceElement.textContent = new Intl.NumberFormat('vi-VN').format(finalPrice) + '₫';
                originalPriceElement.style.display = 'inline';
            }
            if (discountTextElement) {
                discountTextElement.style.display = 'inline';
            }
            if (discountPercentElement) {
                discountPercentElement.textContent = discount;
            }
        } else {
            if (originalPriceElement) {
                originalPriceElement.style.display = 'none';
            }
            if (discountTextElement) {
                discountTextElement.style.display = 'none';
            }
        }

        // Update stock display
        const productQuantityElement = document.getElementById('productQuantity');
        const bookStockElement = document.getElementById('bookStock');
        
        if (productQuantityElement) {
            productQuantityElement.textContent = stock > 0 ? stock : 0;
        }

        if (bookStockElement) {
            let stockText = '';
            let stockClass = '';
            
            if (stock === -1) {
                stockText = 'Sắp Ra Mắt';
                stockClass = 'bg-red-500';
            } else if (stock === -2) {
                stockText = 'Ngưng Kinh Doanh';
                stockClass = 'bg-yellow-500';
            } else if (stock === 0) {
                stockText = 'Hết Hàng Tồn Kho';
                stockClass = 'bg-gray-900';
            } else {
                stockText = 'Còn Hàng';
                stockClass = 'bg-green-500';
            }
            
            bookStockElement.textContent = stockText;
            bookStockElement.className = `font-bold px-3 py-1.5 rounded text-white ${stockClass}`;
        }

        // Update quantity input max value
        const quantityInput = document.getElementById('quantity');
        if (quantityInput && stock > 0) {
            quantityInput.max = stock;
            // Reset quantity if it exceeds stock
            if (parseInt(quantityInput.value) > stock) {
                quantityInput.value = Math.min(parseInt(quantityInput.value), stock);
            }
        }
    }

    // Event listeners
    $(document).ready(function() {
        const formatSelect = document.getElementById('bookFormatSelect');
        if (formatSelect) {
            formatSelect.addEventListener('change', updatePriceAndStock);
        }

        const attributeSelects = document.querySelectorAll('[name^="attributes["]');
        attributeSelects.forEach(select => {
            select.addEventListener('change', updatePriceAndStock);
        });

        // Handle quantity increase/decrease
        const quantityInput = document.getElementById('quantity');
        const incrementBtn = document.getElementById('incrementBtn');
        const decrementBtn = document.getElementById('decrementBtn');

        if (incrementBtn) {
            incrementBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value) || 1;
                const maxValue = parseInt(quantityInput.max) || 999;
                if (currentValue < maxValue) {
                    quantityInput.value = currentValue + 1;
                }
            });
        }

        if (decrementBtn) {
            decrementBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value) || 1;
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
        }

        // Handle add to cart button
        const addToCartBtn = document.getElementById('addToCartBtn');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                addToCart();
            });
        }

        // Handle show more description
        const showMoreBtn = document.getElementById('showMoreBtn');
        const descriptionDiv = document.getElementById('bookDescription');
        
        if (showMoreBtn && descriptionDiv) {
            showMoreBtn.addEventListener('click', function() {
                const isExpanded = descriptionDiv.textContent === descriptionDiv.dataset.full;
                
                if (isExpanded) {
                    descriptionDiv.textContent = descriptionDiv.dataset.short;
                    showMoreBtn.textContent = 'Xem thêm';
                } else {
                    descriptionDiv.textContent = descriptionDiv.dataset.full;
                    showMoreBtn.textContent = 'Thu gọn';
                }
            });
        }

        // Initialize price and stock on page load
        updatePriceAndStock();
    });

    // Add to cart function
    function addToCart() {
        // Check if user is logged in
        @auth
        @else
            if (typeof toastr !== 'undefined') {
                toastr.warning('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng', 'Chưa đăng nhập!', {
                    timeOut: 3000,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                    progressBar: true
                });
            } else {
                alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng');
            }
            setTimeout(() => {
                window.location.href = '{{ route("account.login") }}';
            }, 1500);
            return;
        @endauth

        // Get form data
        const bookId = '{{ $book->id }}';
        const quantity = parseInt(document.getElementById('quantity').value) || 1;
        
        // Get selected format
        const formatSelect = document.getElementById('bookFormatSelect');
        const bookFormatId = formatSelect ? formatSelect.value : null;

        // Get selected attributes
        const attributes = {};
        const attributeValueIds = [];
        const attributeSelects = document.querySelectorAll('[name^="attributes["]');
        
        attributeSelects.forEach(select => {
            if (select.value) {
                const attributeId = select.name.match(/attributes\[(.+)\]/)[1];
                attributes[attributeId] = select.value;
                attributeValueIds.push(select.value);
            }
        });

        // Validate stock
        const stock = parseInt(document.getElementById('productQuantity').textContent) || 0;
        if (stock <= 0 || stock === -1 || stock === -2) {
            if (typeof toastr !== 'undefined') {
                toastr.error('Sản phẩm hiện tại không có hàng', 'Hết hàng!', {
                    timeOut: 3000,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                    progressBar: true
                });
            } else {
                alert('Sản phẩm hiện tại không có hàng');
            }
            return;
        }

        if (quantity > stock) {
            if (typeof toastr !== 'undefined') {
                toastr.error(`Số lượng yêu cầu vượt quá tồn kho. Tồn kho hiện tại: ${stock}`, 'Vượt quá tồn kho!', {
                    timeOut: 5000,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                    progressBar: true
                });
            } else {
                alert(`Số lượng yêu cầu vượt quá tồn kho. Tồn kho hiện tại: ${stock}`);
            }
            return;
        }

        // Disable button and show loading
        const addToCartBtn = document.getElementById('addToCartBtn');
        const originalText = addToCartBtn.textContent;
        addToCartBtn.disabled = true;
        addToCartBtn.textContent = 'Đang thêm...';

        // Send request
        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                book_id: bookId,
                book_format_id: bookFormatId,
                quantity: quantity,
                attribute_value_ids: JSON.stringify(attributeValueIds),
                attributes: attributes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success notification with toastr if available, otherwise use alert
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.success, 'Thành công!', {
                        timeOut: 3000,
                        positionClass: 'toast-top-right',
                        closeButton: true,
                        progressBar: true
                    });
                } else {
                    alert(data.success);
                }
                
                // Update stock if provided
                if (data.stock !== undefined) {
                    document.getElementById('productQuantity').textContent = data.stock;
                    updatePriceAndStock(); // Refresh stock status
                }
                
                // Show cart count update notification
                setTimeout(() => {
                    if (typeof toastr !== 'undefined') {
                        toastr.info('Xem giỏ hàng của bạn', 'Tip', {
                            timeOut: 2000,
                            onclick: function() {
                                window.location.href = '{{ route("cart.index") }}';
                            }
                        });
                    }
                }, 1000);
                
            } else if (data.error) {
                // Show error notification with toastr if available, otherwise use alert
                if (typeof toastr !== 'undefined') {
                    toastr.error(data.error, 'Lỗi!', {
                        timeOut: 5000,
                        positionClass: 'toast-top-right',
                        closeButton: true,
                        progressBar: true
                    });
                } else {
                    alert(data.error);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('Có lỗi xảy ra khi thêm vào giỏ hàng', 'Lỗi mạng!', {
                    timeOut: 5000,
                    positionClass: 'toast-top-right',
                    closeButton: true,
                    progressBar: true
                });
            } else {
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
            }
        })
        .finally(() => {
            // Restore button
            addToCartBtn.disabled = false;
            addToCartBtn.textContent = originalText;
        });
    }
</script>
@endpush

