@extends('layouts.app')

@section('content')
<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-white bg-opacity-80 z-50 hidden">
    <div class="h-full w-full flex items-center justify-center">
        <div class="relative">
            <div class="animate-pulse flex flex-col items-center space-y-4">
                <div class="loader-heart"></div>
                <p class="text-gray-600 bg-gradient-to-r from-red-500 to-pink-500 bg-clip-text text-transparent font-medium">
                    Đang tải...
                </p>
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-red-50 to-pink-50 filter blur-xl opacity-50 animate-pulse-slow"></div>
        </div>
    </div>
</div>

<!-- Keyboard Shortcuts Help -->
<div id="shortcuts-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-keyboard text-red-500"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Phím tắt</h3>
                        <div class="mt-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Chế độ xem Lưới/Danh sách</span>
                                <kbd class="px-2 py-1 bg-gray-100 rounded">G</kbd>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Xóa tất cả</span>
                                <kbd class="px-2 py-1 bg-gray-100 rounded">Ctrl + D</kbd>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Sắp xếp</span>
                                <kbd class="px-2 py-1 bg-gray-100 rounded">S</kbd>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Trợ giúp</span>
                                <kbd class="px-2 py-1 bg-gray-100 rounded">?</kbd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="toggleShortcutsModal()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <!-- Header với 3D effect -->
    <div class="relative mb-8 perspective">
        <div class="bg-gradient-to-r from-red-50 to-pink-50 p-8 rounded-xl shadow-sm border border-gray-200 
            transform hover:rotate-x-1 transition-all duration-500 relative overflow-hidden">
            <!-- Animated Background -->
            <div class="absolute inset-0 opacity-10">
                <div class="hearts-bg"></div>
            </div>
            
            <div class="relative">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="space-y-2 text-center md:text-left">
                        <h1 class="text-3xl md:text-4xl font-bold flex items-center gap-3 animate-title">
                            <i class="fas fa-heart text-red-500 animate-beat"></i>
                            <span class="bg-gradient-to-r from-red-500 to-pink-500 text-transparent bg-clip-text">
                                DANH SÁCH YÊU THÍCH
                            </span>
                        </h1>
                        <p class="text-gray-500 max-w-xl">
                            Nơi lưu giữ những cuốn sách đã chạm đến trái tim bạn
                        </p>
                    </div>
                    <nav class="flex items-center gap-3 text-sm">
                        <a href="/" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white shadow-sm 
                            hover:shadow-md transition-all duration-200 group">
                            <i class="fas fa-home text-red-500 group-hover:scale-110 transition-transform duration-200"></i>
                            <span class="text-gray-600">Trang chủ</span>
                        </a>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                        <span class="px-4 py-2 rounded-lg bg-red-50 text-red-500 font-medium">
                            Danh sách yêu thích
                        </span>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Danh sách sách -->
        <div class="lg:w-3/4 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-pink-500 
                        flex items-center justify-center text-white font-bold shadow-lg">
                        {{ $wishlist->count() }}
                    </div>
                    <p class="text-gray-600">sách yêu thích</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">|</span>
                    <button class="flex items-center gap-2 text-gray-500 hover:text-red-500 transition-colors duration-200"
                        onclick="toggleView()">
                        <i class="fas fa-th-large" id="view-icon"></i>
                        <span id="view-text">Dạng lưới</span>
                    </button>
                </div>
            </div>

            @if($wishlist->isEmpty())
                <div class="text-center py-16 bg-gradient-to-b from-gray-50 to-white rounded-xl 
                    border-2 border-dashed border-gray-300">
                    <div class="mb-8 relative">
                        <div class="absolute inset-0 animate-pulse-slow">
                            <i class="fas fa-heart text-red-100 text-9xl transform -rotate-12"></i>
                        </div>
                        <div class="relative">
                            <i class="fas fa-book text-gray-300 text-6xl"></i>
                        </div>
                    </div>
                    <div class="max-w-md mx-auto space-y-4">
                        <p class="text-gray-500 text-xl font-medium">Danh sách yêu thích trống</p>
                        <p class="text-gray-400">Hãy khám phá và thêm những cuốn sách bạn yêu thích vào đây!</p>
                        <a href="/books" 
                            class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-red-500 to-pink-500 
                            text-white rounded-xl hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200">
                            <i class="fas fa-book-open"></i>
                            <span>Khám phá sách ngay</span>
                        </a>
                    </div>
                </div>
            @else
                <div id="books-container" class="grid grid-cols-1 gap-6 transition-all duration-300">
                    @foreach($wishlist as $item)
                        <div class="book-card group" data-book-id="{{ $item->book_id }}">
                            <div class="bg-white rounded-xl shadow-sm hover:shadow-xl border border-gray-100 
                                transition-all duration-300 hover:scale-[1.02] overflow-hidden">
                                <div class="flex gap-6 p-6">
                                    <!-- Ảnh sách -->
                                    <div class="w-1/4 aspect-[3/4] rounded-lg overflow-hidden perspective">
                                        <div class="w-full h-full relative transform transition-all duration-700 
                                            preserve-3d group-hover:rotate-y-6">
                                            <img src="{{ asset($item->cover_image) }}" 
                                                class="absolute inset-0 w-full h-full object-cover backface-hidden" 
                                                alt="{{ $item->title }}">
                                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-black/60 to-transparent 
                                                flex items-center justify-center backface-hidden rotate-y-180">
                                                <a href="/book/{{ $item->book_id }}" 
                                                    class="text-white hover:text-red-200 flex items-center gap-2">
                                                    <i class="fas fa-eye"></i>
                                                    <span>Xem chi tiết</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thông tin sách -->
                                    <div class="flex-1 flex flex-col justify-between">
                                      <div>
                                        <div class="flex items-start justify-between">
                                          <h3 class="text-xl font-bold group-hover:text-red-500 
                                                     transition-colors duration-200 line-clamp-2 max-w-[75%]">
                                            {{ $item->title }}
                                          </h3>
                                          <button onclick="removeFromWishlist('{{ $item->book_id }}')"
                                                  class="w-8 h-8 rounded-full bg-gray-50 hover:bg-red-50 
                                                         flex items-center justify-center text-gray-400 
                                                         hover:text-red-500 transition-all duration-200"
                                                  title="Xóa khỏi danh sách yêu thích">
                                            <i class="fas fa-times"></i>
                                          </button>
                                        </div>
                                        
                                        <div class="mt-3 grid grid-cols-2 gap-x-6 gap-y-2 text-gray-600">
                                          <p class="flex items-center gap-2">
                                            <i class="fas fa-user-edit text-red-400"></i>
                                            <span>{{ $item->author_name }}</span>
                                          </p>
                                          <p class="flex items-center gap-2">
                                            <i class="fas fa-book text-green-500"></i>
                                            <span>Loại sách: {{ $item->category_name ?? 'Chưa cập nhật' }}</span>
                                          </p>
                                          <p class="flex items-center gap-2">
                                            <i class="fas fa-building text-blue-500"></i>
                                            <span>Nhà xuất bản: {{ $item->brand_name ?? 'Chưa cập nhật' }}</span>
                                          </p>
                                          <p class="flex items-center gap-2 text-sm text-gray-500">
                                            <i class="fas fa-clock text-blue-400"></i>
                                            <span>Đã thêm {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                          </p>
                                        </div>
                                      </div>
                                    
                                      <div class="mt-6 flex items-center justify-end gap-4">
                                        <a href="/book/{{ $item->book_id }}" 
                                           class="flex items-center gap-2 px-4 py-2 text-gray-500 
                                                  hover:text-red-500 transition-colors duration-200">
                                          <i class="fas fa-info-circle"></i>
                                          <span>Chi tiết</span>
                                        </a>
                                        <button onclick="addToCart('{{ $item->book_id }}')"
                                                class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r 
                                                       from-red-500 to-pink-500 text-white rounded-lg hover:shadow-lg 
                                                       transform hover:-translate-y-0.5 transition-all duration-200">
                                          <i class="fas fa-shopping-cart"></i>
                                          <span>Thêm vào giỏ</span>
                                        </button>
                                      </div>
                                    </div>
                                    
                                  
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Phân trang -->
                <div class="mt-8">
                    {{ $wishlist->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:w-1/4">
            <div class="sticky top-4 space-y-6">
                <!-- Thống kê -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden
                    transform transition-all duration-300 hover:shadow-xl">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-6 pb-2 border-b flex items-center gap-2">
                            <i class="fas fa-chart-pie text-red-500"></i>
                            <span class="bg-gradient-to-r from-red-500 to-pink-500 text-transparent bg-clip-text">
                                THỐNG KÊ
                            </span>
                        </h2>
                        
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-pink-50 opacity-50"></div>
                            <div class="relative p-6 text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full 
                                    bg-gradient-to-r from-red-500 to-pink-500 text-white text-2xl font-bold mb-3
                                    shadow-lg animate-pulse-slow">
                                    {{ $statistics['total'] }}
                                </div>
                                <p class="text-gray-600">Tổng số sách yêu thích</p>
                            </div>
                        </div>

                        @if($wishlist->isNotEmpty())
                            <div class="space-y-4 mt-6">
                                <!-- Sorting Dropdown -->
                                <div class="relative">
                                    <select id="sort-select" 
                                        class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg
                                        appearance-none cursor-pointer focus:outline-none focus:ring-2
                                        focus:ring-red-500 focus:border-red-500">
                                        <option value="date-desc">Mới nhất trước</option>
                                        <option value="date-asc">Cũ nhất trước</option>
                                        <option value="title-asc">Theo tên A-Z</option>
                                        <option value="title-desc">Theo tên Z-A</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                                        <i class="fas fa-sort"></i>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="grid grid-cols-2 gap-4">
                                    <button onclick="toggleShortcutsModal()"
                                        class="flex items-center justify-center gap-2 px-4 py-3
                                        bg-gray-50 text-gray-700 rounded-lg hover:bg-blue-50
                                        hover:text-blue-500 transition-all duration-200 border
                                        border-gray-200 hover:border-blue-200">
                                        <i class="fas fa-keyboard"></i>
                                        <span>Phím tắt</span>
                                    </button>
                                    
                                    <button onclick="removeAllFromWishlist()"
                                        class="flex items-center justify-center gap-2 px-4 py-3
                                        bg-gray-50 text-gray-700 rounded-lg hover:bg-red-50
                                        hover:text-red-500 transition-all duration-200 border
                                        border-gray-200 hover:border-red-200">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Xóa tất cả</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <p class="text-sm font-medium flex items-center gap-2 mb-4">
                            <i class="fas fa-lightbulb text-yellow-400"></i>
                            <span class="bg-gradient-to-r from-red-500 to-pink-500 text-transparent bg-clip-text">
                                MẸO HAY
                            </span>
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 
                                transition-all duration-200 group cursor-pointer">
                                <i class="fas fa-check text-green-500 group-hover:scale-110 
                                    transition-transform duration-200"></i>
                                <span class="text-gray-600 group-hover:text-gray-900 transition-colors duration-200">
                                    Thêm sách vào giỏ hàng để mua ngay
                                </span>
                            </li>
                            <li class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 
                                transition-all duration-200 group cursor-pointer">
                                <i class="fas fa-check text-green-500 group-hover:scale-110 
                                    transition-transform duration-200"></i>
                                <span class="text-gray-600 group-hover:text-gray-900 transition-colors duration-200">
                                    Theo dõi sách yêu thích của bạn
                                </span>
                            </li>
                            <li class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 
                                transition-all duration-200 group cursor-pointer">
                                <i class="fas fa-check text-green-500 group-hover:scale-110 
                                    transition-transform duration-200"></i>
                                <span class="text-gray-600 group-hover:text-gray-900 transition-colors duration-200">
                                    Dễ dàng tìm lại sách đã thích
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/wishlist.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
