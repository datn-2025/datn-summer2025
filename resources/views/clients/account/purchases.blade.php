@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">

    {{-- Sidebar --}}
    <aside class="user-sidebar w-64 bg-white shadow-sm border-r border-gray-200 h-screen fixed left-0 top-0 pt-16 transition-transform z-40">
        <div class="p-4 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=4f46e5&color=fff' }}"
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

        {{-- Navigation --}}
        @php
            $currentRoute = request()->route()->getName();
            $menuItems = [
                ['route' => 'account.profile', 'icon' => 'fas fa-user', 'title' => 'Thông tin', 'active' => $currentRoute === 'account.profile'],
                ['route' => 'account.orders.index', 'icon' => 'fas fa-shopping-bag', 'title' => 'Đơn hàng', 'active' => $currentRoute === 'account.orders.index'],
                ['route' => 'account.purchase', 'icon' => 'fas fa-star', 'title' => 'Đánh giá của tôi', 'active' => $currentRoute === 'account.purchase'],
                ['route' => 'home', 'icon' => 'fas fa-home', 'title' => 'Trang chủ', 'active' => $currentRoute === 'home'],
            ];
        @endphp

        <nav class="mt-4 space-y-1 px-2">
            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition
                          {{ $item['active'] ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="{{ $item['icon'] }} w-5 text-center mr-3"></i>
                    <span>{{ $item['title'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition">
                    <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                    Đăng xuất
                </button>
            </form>
        </div>
    </aside>

    {{-- Overlay cho mobile --}}
    <div class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden" id="sidebar-overlay"></div>

    {{-- Main Content --}}
    <main class="flex-1 py-8 overflow-x-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                    <h1 class="text-2xl font-bold text-white">Đánh giá của tôi</h1>
                </div>

                {{-- Tabs --}}
                <div class="p-8">
                    <div class="bg-slate-100 rounded-xl p-2 mb-8 flex space-x-1">
                        @foreach ([1 => 'Tất cả đơn hàng', 2 => 'Chưa đánh giá', 3 => 'Đã đánh giá'] as $type => $label)
                            <a href="{{ route('account.purchase', ['type' => $type]) }}"
                               class="flex-1 text-center px-6 py-3 text-sm font-medium rounded-lg transition
                                   {{ request('type', '1') == $type ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Orders List --}}
                    <div class="space-y-6">
                        @forelse($orders as $order)
                            <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition">
                                {{-- Order Header --}}
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-slate-50 border-b">
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900">Đơn hàng #{{ $order->id }}</h3>
                                        <p class="text-sm text-slate-500">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2 sm:mt-0">
                                        Đã hoàn thành
                                    </span>
                                </div>

                                {{-- Order Items --}}
                                <div class="p-6 space-y-6">
                                    @foreach($order->orderItems as $item)
                                        @include('partials.order-item-review', ['order' => $order, 'item' => $item])
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            {{-- Empty State --}}
                            <div class="text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                    <i class="fas fa-box-open text-blue-600 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-slate-900 mb-1">Không có đơn hàng nào</h3>
                                <p class="text-slate-500">Bạn chưa có đơn hàng nào để hiển thị.</p>
                            </div>
                        @endforelse

                        {{-- Pagination --}}
                        @if($orders->hasPages())
                            <div class="mt-8 flex justify-center">
                                {{ $orders->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Sidebar toggle script --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.user-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.style.overflow = sidebar.classList.contains('-translate-x-full') ? 'auto' : 'hidden';
    }

    toggleBtn?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
});
</script>
@endpush
@endsection
