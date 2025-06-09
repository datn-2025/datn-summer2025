@extends('layouts.account')

@section('account_content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h1 class="text-2xl font-bold text-white">Quản lý đơn hàng</h1>
            </div>
            
            <div class="p-8">
                <!-- Navigation Tabs -->
                <div class="mb-8 border-b border-gray-200">
                    <ul class="flex flex-wrap -mb-px" id="orderTabs" role="tablist">
                        @php
                            $tabs = [
                                ['id' => 'all', 'name' => 'Tất cả', 'status' => null],
                                ['id' => 'pending', 'name' => 'Chờ xác nhận', 'status' => 'Chờ xác nhận'],
                                ['id' => 'confirmed', 'name' => 'Đã xác nhận', 'status' => 'Đã xác nhận'],
                                ['id' => 'preparing', 'name' => 'Đang chuẩn bị', 'status' => 'Đang chuẩn bị'],
                                ['id' => 'shipping', 'name' => 'Đang giao hàng', 'status' => 'Đang giao hàng'],
                                ['id' => 'delivered', 'name' => 'Đã giao hàng', 'status' => 'Đã giao thành công'],
                                ['id' => 'received', 'name' => 'Đã nhận hàng', 'status' => 'Đã nhận hàng'],
                                ['id' => 'completed', 'name' => 'Thành công', 'status' => 'Thành công'],
                                ['id' => 'cancelled', 'name' => 'Đã hủy', 'status' => 'Đã hủy'],
                                ['id' => 'failed', 'name' => 'Giao thất bại', 'status' => 'Giao thất bại'],
                                ['id' => 'refunded', 'name' => 'Đã hoàn tiền', 'status' => 'Đã hoàn tiền']
                            ];
                        @endphp

                        @foreach($tabs as $tab)
                            <li class="mr-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg 
                                    {{ request('status') === $tab['status'] || 
                                       (!request('status') && $tab['id'] === 'all') ? 
                                       'text-blue-600 border-blue-600' : 
                                       'border-transparent hover:text-gray-600 hover:border-gray-300' }}"
                                    id="{{ $tab['id'] }}-tab" 
                                    data-tabs-target="#{{ $tab['id'] }}" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="{{ $tab['id'] }}"
                                    onclick="filterOrders('{{ $tab['status'] }}')">
                                    {{ $tab['name'] }}
                                    @if(isset($orderCounts[$tab['status'] ?? 'all']))
                                        <span class="ml-1 bg-gray-200 text-gray-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                            {{ $orderCounts[$tab['status'] ?? 'all'] }}
                                        </span>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Order List -->
                <div class="space-y-6">
                    @forelse($orders as $order)
                        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                            <!-- Order Header -->
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                                <div>
                                    <span class="text-sm text-gray-600">Mã đơn hàng: </span>
                                    <span class="font-medium">{{ $order->code }}</span>
                                    <span class="mx-2">•</span>
                                    <span class="text-sm text-gray-600">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $orderStatusColors[$order->orderStatus->name] ?? 'bg-gray-200 text-gray-800' }}">
                                        {{ $order->orderStatus->name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Order Items -->
                            @foreach($order->orderItems as $item)
                                <div class="p-6 border-b border-gray-100 flex">
                                    <div class="flex-shrink-0 h-20 w-20 bg-gray-200 rounded-md overflow-hidden">
                                        @if($item->book->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $item->book->images->first()->path) }}" 
                                                 alt="{{ $item->book->title }}" 
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-gray-300 flex items-center justify-center">
                                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            {{ $item->book->title }}
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Số lượng: {{ $item->quantity }}
                                        </p>
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ number_format($item->price) }} đ
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Order Footer -->
                            <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                                <!-- <div class="text-sm">
                                    <span class="text-gray-600">Tổng cộng:</span>
                                    <span class="ml-2 font-medium text-gray-900">
                                        {{ number_format($order->total) }} đ
                                    </span>
                                </div> -->
                                <div class="text-sm">
                                    <span class="text-gray-600">Tổng cộng:</span>
                                    <span class="ml-2 font-medium text-gray-900">
                                        @php
                                            // Tính toán giảm giá nếu có voucher
                                            $discountAmount = 0;
                                            if ($order->voucher) {
                                                $discountByPercent = $order->total_amount * ($order->voucher->discount_percent / 100);
                                                $discountAmount = min($discountByPercent, $order->voucher->max_discount);
                                            }
                                            // Tính tổng tiền cuối cùng
                                            $total = $order->total_amount - $discountAmount + $order->shipping_fee;
                                        @endphp
                                        {{ number_format($total) }} đ
                                    </span>
                                </div>
                                <div class="space-x-3">
                                    <a href="{{ route('account.orders.show', $order->id) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Xem chi tiết
                                    </a>
                                    
                                    @if(in_array($order->orderStatus->name, ['Chờ xác nhận', 'Đã xác nhận', 'Đang chuẩn bị']))
                                        <button type="button" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                onclick="confirmCancel({{ $order->id }})">
                                            Hủy đơn hàng
                                        </button>
                                        <form id="cancel-form-{{ $order->id }}" 
                                              action="{{ route('account.orders.cancel', $order->id) }}" 
                                              method="POST" class="hidden">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h18v18H3V3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5h6v2H9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 9h10v10H7z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Không có đơn hàng nào</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Bạn chưa có đơn hàng nào trong mục này.
                            </p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="mt-8">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function filterOrders(status) {
        const url = new URL(window.location.href);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    }

    function confirmCancel(orderId) {
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
            document.getElementById(`cancel-form-${orderId}`).submit();
        }
    }

    // Initialize tab state based on URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const tabId = status ? status.toLowerCase().replace(/\s+/g, '-') : 'all';
        const tabButton = document.getElementById(`${tabId}-tab`);
        if (tabButton) {
            //tabButton.click();
        }
    });
</script>

<style>
    [role="tab"][aria-selected="true"] {
        color: #2563eb;
        border-bottom-color: #2563eb;
        border-bottom-width: 2px;
    }
</style>
@endpush

@endsection