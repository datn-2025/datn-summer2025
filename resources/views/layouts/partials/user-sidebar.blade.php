<!-- User Sidebar -->
<aside class="w-64 bg-white shadow-sm border-r border-gray-200 h-screen fixed left-0 top-0 pt-16 transition-all duration-300 z-40">
    <!-- User Profile -->
    <div class="p-4 border-b border-gray-100">
        <div class="flex items-center space-x-3">
            <div class="relative">
                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4f46e5&color=fff' }}" 
                     alt="User Avatar" 
                     class="w-12 h-12 rounded-full object-cover border-2 border-indigo-100">
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
            </div>
            <div>
                <h3 class="font-medium text-gray-800">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-4">
        <div class="px-2 space-y-1">
            @php
                $currentRoute = request()->route()->getName();
                $menuItems = [
                    [
                        'route' => 'account.index',
                        'icon' => 'fas fa-home',
                        'title' => 'Tổng quan',
                        'active' => $currentRoute === 'account.index'
                    ],
                    [
                        'route' => 'account.orders',
                        'icon' => 'fas fa-shopping-bag',
                        'title' => 'Đơn hàng',
                        'badge' => 0,
                        'active' => $currentRoute === 'account.orders'
                    ],
                    [
                        'route' => 'account.purchase',
                        'icon' => 'fas fa-star',
                        'title' => 'Đánh giá',
                        'badge' => 0,
                        'active' => $currentRoute === 'account.purchase'
                    ],
                    [
                        'route' => 'home',
                        'icon' => 'fas fa-star',
                        'title' => 'Đánh giá của tôi',
                        'active' => str_starts_with($currentRoute, 'account.reviews')
                    ],
                    // Thêm form đăng xuất
                    [
                        'route' => 'logout',
                        'icon' => 'fas fa-sign-out-alt',
                        'title' => 'Đăng xuất',
                        'is_form' => true,
                        'form_action' => route('logout'),
                        'form_method' => 'POST',
                        'active' => false
                    ]
                ];
            @endphp

            @foreach($menuItems as $item)
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg mx-2 transition-colors
                          {{ $item['active'] ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="{{ $item['icon'] }} w-5 text-center mr-3"></i>
                    <span>{{ $item['title'] }}</span>
                    @if(isset($item['badge']) && $item['badge'])
                        <span class="ml-auto bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        <!-- Logout -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                    <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                    Đăng xuất
                </button>
            </form>
        </div>
    </nav>
</aside>

<!-- 
$menuItems = [
    [
        'route' => 'user.dashboard',
        'icon' => 'fas fa-home',
        'title' => 'Tổng quan',
        'active' => str_starts_with($currentRoute, 'user.dashboard')
    ],
    [
        'route' => 'user.orders',
        'icon' => 'fas fa-shopping-bag',
        'title' => 'Đơn hàng',
        'badge' => auth()->user()->unreadNotifications->count() > 0 ? auth()->user()->unreadNotifications->count() : null,
        'active' => str_starts_with($currentRoute, 'user.orders')
    ],
    [
        'route' => 'user.reviews',
        'icon' => 'fas fa-star',
        'title' => 'Đánh giá của tôi',
        'active' => str_starts_with($currentRoute, 'user.reviews')
    ],
    [
        'route' => 'user.wishlist',
        'icon' => 'fas fa-heart',
        'title' => 'Yêu thích',
        'active' => str_starts_with($currentRoute, 'user.wishlist')
    ],
    [
        'route' => 'user.addresses',
        'icon' => 'fas fa-map-marker-alt',
        'title' => 'Địa chỉ',
        'active' => str_starts_with($currentRoute, 'user.addresses')
    ],
    [
        'route' => 'user.profile',
        'icon' => 'fas fa-user',
        'title' => 'Thông tin tài khoản',
        'active' => str_starts_with($currentRoute, 'user.profile')
    ],
    [
        'route' => 'user.settings',
        'icon' => 'fas fa-cog',
        'title' => 'Cài đặt',
        'active' => str_starts_with($currentRoute, 'user.settings')
    ]
];
 -->
<!-- Overlay for mobile -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden" id="sidebar-overlay" style="display: none;"></div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const sidebar = document.querySelector('.user-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.style.display = sidebar.classList.contains('-translate-x-full') ? 'none' : 'block';
        document.body.style.overflow = sidebar.classList.contains('-translate-x-full') ? 'auto' : 'hidden';
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }
    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }

    // Close sidebar when clicking outside on desktop
    function handleResize() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize();
});
</script>
<style>
    .user-sidebar {
        transition: transform 0.3s ease-in-out;
    }

    @media (max-width: 1023px) {
        .user-sidebar {
            transform: translateX(-100%);
        }
        .user-sidebar.-translate-x-full {
            transform: translateX(-100%);
        }
    }
</style>
@endpush