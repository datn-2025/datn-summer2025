@extends('layouts.account.layout')

@section('account_content')
<div class="bg-white border border-black shadow mb-8" style="border-radius:0;">
    <div class="px-8 py-6 border-b border-black bg-black">
        <h1 class="text-2xl font-bold text-white uppercase tracking-wide">Đánh giá của tôi</h1>
    </div>
    <div class="p-8">
        <div class="flex space-x-1 mb-8 border-b border-black">
            @foreach ([1 => 'Tất cả đơn hàng', 2 => 'Chưa đánh giá', 3 => 'Đã đánh giá'] as $type => $label)
                <a href="{{ route('account.purchase', ['type' => $type]) }}"
                   class="flex-1 text-center px-6 py-3 text-base font-semibold border-b-2 transition
                       {{ request('type', '1') == $type ? 'border-black text-black bg-white' : 'border-transparent text-gray-500 hover:text-black hover:bg-gray-100' }}"
                   style="border-radius:0;">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="bg-white border border-black shadow transition hover:shadow-lg" style="border-radius:0;">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-gray-50 border-b border-black">
                        <div>
                            <h3 class="text-lg font-bold text-black">Đơn hàng #{{ $order->id }}</h3>
                            <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-black text-white border border-black" style="border-radius:0;">
                            Đã hoàn thành
                        </span>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($order->orderItems as $item)
                            @include('partials.order-item-review', ['order' => $order, 'item' => $item])
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-black text-white mb-4" style="border-radius:0;">
                        <i class="fas fa-box-open text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-black mb-1">Không có đơn hàng nào</h3>
                    <p class="text-gray-600">Bạn chưa có đơn hàng nào để hiển thị.</p>
                </div>
            @endforelse
            @if($orders->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
