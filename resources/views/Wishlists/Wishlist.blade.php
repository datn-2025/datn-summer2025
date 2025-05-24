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

<script>
// View state management
let isGridView = localStorage.getItem('wishlistView') === 'grid';
let currentSort = localStorage.getItem('wishlistSort') || 'date-desc';

// Update view on load
document.addEventListener('DOMContentLoaded', () => {
    updateViewState();
    initializeKeyboardShortcuts();
    initializeSortingDropdown();
});

function updateViewState() {
    const container = document.getElementById('books-container');
    const icon = document.getElementById('view-icon');
    const text = document.getElementById('view-text');
    
    container.style.opacity = '0';
    
    setTimeout(() => {
        if (isGridView) {
            container.classList.remove('grid-cols-1');
            container.classList.add('grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-2');
            icon.classList.remove('fa-th-large');
            icon.classList.add('fa-list');
            text.textContent = 'Dạng danh sách';
        } else {
            container.classList.remove('grid-cols-2', 'md:grid-cols-3', 'lg:grid-cols-2');
            container.classList.add('grid-cols-1');
            icon.classList.remove('fa-list');
            icon.classList.add('fa-th-large');
            text.textContent = 'Dạng lưới';
        }
        
        container.style.opacity = '1';
    }, 300);
}

function toggleView() {
    isGridView = !isGridView;
    localStorage.setItem('wishlistView', isGridView ? 'grid' : 'list');
    updateViewState();
}

function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
        // Toggle view mode with 'G' key
        if (e.key.toLowerCase() === 'g') {
            toggleView();
        }
        
        // Show shortcuts modal with '?' key
        if (e.key === '?') {
            toggleShortcutsModal();
        }
        
        // Delete all items with Ctrl+D
        if (e.ctrlKey && e.key.toLowerCase() === 'd') {
            e.preventDefault();
            removeAllFromWishlist();
        }
        
        // Open sort dropdown with 'S' key
        if (e.key.toLowerCase() === 's') {
            document.getElementById('sort-dropdown')?.click();
        }
    });
}

function initializeSortingDropdown() {
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.value = currentSort;
        sortSelect.addEventListener('change', handleSort);
    }
}

function handleSort(e) {
    const sortBy = e.target.value;
    currentSort = sortBy;
    localStorage.setItem('wishlistSort', sortBy);
    
    const container = document.getElementById('books-container');
    const items = Array.from(container.children);
    
    items.sort((a, b) => {
        const dateA = new Date(a.dataset.date);
        const dateB = new Date(b.dataset.date);
        const titleA = a.dataset.title;
        const titleB = b.dataset.title;
        
        switch(sortBy) {
            case 'date-desc':
                return dateB - dateA;
            case 'date-asc':
                return dateA - dateB;
            case 'title-asc':
                return titleA.localeCompare(titleB);
            case 'title-desc':
                return titleB.localeCompare(titleA);
            default:
                return 0;
        }
    });
    
    container.style.opacity = '0';
    setTimeout(() => {
        container.innerHTML = '';
        items.forEach(item => container.appendChild(item));
        container.style.opacity = '1';
    }, 300);
}

function toggleShortcutsModal() {
    const modal = document.getElementById('shortcuts-modal');
    modal.classList.toggle('hidden');
}

function showLoading() {
    document.getElementById('loading-overlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-overlay').classList.add('hidden');
}

// Configure Toastr
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 3000,
    extendedTimeOut: 1000,
    preventDuplicates: true,
    newestOnTop: true,
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

function showNotification(message, type = 'success') {
    const icons = {
        success: '<i class="fas fa-check-circle mr-2"></i>',
        error: '<i class="fas fa-exclamation-circle mr-2"></i>',
        warning: '<i class="fas fa-exclamation-triangle mr-2"></i>',
        info: '<i class="fas fa-info-circle mr-2"></i>'
    };

    // Debounce notifications to prevent spamming
    if (window.notificationTimeout) {
        clearTimeout(window.notificationTimeout);
    }

    window.notificationTimeout = setTimeout(() => {
        toastr[type](icons[type] + message);
    }, 100);
}

function showLoadingNotification(message = 'Đang xử lý...') {
    return toastr.info(
        '<i class="fas fa-spinner fa-spin mr-2"></i>' + message,
        null,
        {
            timeOut: 0,
            extendedTimeOut: 0,
            closeButton: false,
            progressBar: false
        }
    );
}

// Debounced function to prevent multiple rapid calls
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

// Handle API calls with loading states
async function handleApiCall(url, options = {}, loadingMsg = 'Đang xử lý...') {
    const loadingToast = showLoadingNotification(loadingMsg);
    
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                ...options.headers
            }
        });
        
        const data = await response.json();
        toastr.clear(loadingToast);
        
        if (!response.ok) {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
        
        return data;
    } catch (error) {
        toastr.clear(loadingToast);
        throw error;
    }
}

// Remove all items with confirmation and animation
async function removeAllFromWishlist() {
    const confirmResult = await Swal.fire({
        title: 'Xóa tất cả?',
        text: 'Bạn có chắc chắn muốn xóa tất cả sách khỏi danh sách yêu thích?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa tất cả',
        cancelButtonText: 'Hủy',
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280'
    });

    if (!confirmResult.isConfirmed) {
        return;
    }

    try {
        // Animate all items fading out
        const items = document.querySelectorAll('.book-card');
        items.forEach((item, index) => {
            setTimeout(() => {
                item.style.transform = 'scale(0.8)';
                item.style.opacity = '0';
            }, index * 100);
        });

        const data = await handleApiCall('/wishlist/delete-all', {
            method: 'POST'
        }, 'Đang xóa tất cả...');

        if (data.success) {
            showNotification('Đã xóa tất cả sách khỏi danh sách yêu thích', 'success');
            setTimeout(() => location.reload(), 800);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message || 'Đã có lỗi xảy ra', 'error');
        
        // Restore animations if error occurs
        const items = document.querySelectorAll('.book-card');
        items.forEach(item => {
            item.style.transform = '';
            item.style.opacity = '';
        });
    }
}
async function addToCart(bookId, bookFormatId = null, attributes = null) {
    try {
        const bodyData = { book_id: bookId };
        if (bookFormatId) bodyData.book_format_id = bookFormatId;
        if (attributes) bodyData.attributes = attributes;

        const response = await fetch('/wishlist/add-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(bodyData)
        });

        const data = await response.json();

        if (data.success) {
            showNotification(data.message || 'Đã thêm sách vào giỏ hàng', 'success');
        } else {
            showNotification(data.message || 'Thêm giỏ hàng thất bại', 'error');
        }
    } catch (error) {
        showNotification('Lỗi kết nối server', 'error');
    }
}




function removeFromWishlist(bookId) {
    if (confirm('Bạn có chắc chắn muốn xóa sách này khỏi danh sách yêu thích?')) {
        fetch('/wishlist/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ book_id: bookId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Đã xóa sách khỏi danh sách yêu thích');
                const bookElement = document.querySelector(`[data-book-id="${bookId}"]`);
                if (bookElement) {
                    bookElement.classList.add('fade-out');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    location.reload();
                }
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Đã có lỗi xảy ra', 'error');
        });
    }
}
</script>

<style>
@keyframes beat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

.animate-beat {
    animation: beat 1s ease-in-out infinite;
}

@keyframes pulse-slow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse-slow {
    animation: pulse-slow 3s ease-in-out infinite;
}

.loader-heart {
    width: 40px;
    height: 40px;
    background: 
        radial-gradient(circle at 60% 65%, red 64%, transparent 65%) top left,
        radial-gradient(circle at 40% 65%, red 64%, transparent 65%) top right,
        linear-gradient(to bottom left, red 43%, transparent 43%) bottom right,
        linear-gradient(to bottom right, red 43%, transparent 43%) bottom left;
    background-size: 50% 50%;
    background-repeat: no-repeat;
    animation: rotate 1s linear infinite;
}

@keyframes rotate {
    to { transform: rotate(360deg); }
}

.perspective {
    perspective: 1000px;
}

.backface-hidden {
    backface-visibility: hidden;
}

.preserve-3d {
    transform-style: preserve-3d;
}

.hearts-bg {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 30c-2-2-4-4-4-8 0-4 2-6 4-6s4 2 4 6c0 4-2 6-4 8zm0 0c2-2 4-4 4-8 0-4-2-6-4-6s-4 2-4 6c0 4 2 6 4 8z' fill='%23FF0000' fill-opacity='0.05'/%3E%3C/svg%3E");
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Loaders */
.loader {
    width: 24px;
    height: 24px;
    border: 3px solid #FFF;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
}

@keyframes rotation {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Hover Effects */
.hover-scale {
    transition: transform 0.2s ease;
}

.hover-scale:hover {
    transform: scale(1.05);
}

/* Shimmer Effect */
.shimmer {
    background: linear-gradient(
        90deg,
        rgba(255,255,255,0) 0%,
        rgba(255,255,255,0.2) 50%,
        rgba(255,255,255,0) 100%
    );
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection