@extends('layouts.account.layout')

@section('account_content')
<div class="bg-white border border-black shadow mb-8" style="border-radius:0;">
    <div class="px-8 py-6 border-b border-black bg-black">
        <h1 class="text-2xl font-bold text-white uppercase tracking-wide">Đánh giá của tôi</h1>
    </div>
    <div class="p-8">
        <div class="flex space-x-1 mb-8 border-b border-black">
            @foreach ([1 => 'Tất cả đánh giá', 2 => 'Chưa đánh giá', 3 => 'Đã đánh giá'] as $type => $label)
                <a href="{{ route('account.purchase', ['type' => $type]) }}"
                   class="flex-1 text-center px-6 py-3 text-base font-semibold border-b-2 transition
                       {{ request('type', '1') == $type ? 'border-black text-black bg-white' : 'border-transparent text-gray-500 hover:text-black hover:bg-gray-100' }}"
                   style="border-radius:0;">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        <div class="space-y-6">
            @if(request('type') == 1)
                <div>
                    <h3 class="text-xl font-semibold text-black">Chưa đánh giá</h3>
                    @forelse($orders->where('reviewed', false)->sortBy('completed_at') as $order)
                        <div class="bg-white border border-black shadow transition hover:shadow-lg" style="border-radius:0;">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-gray-50 border-b border-black">
                                <div>
                                    <h3 class="text-lg font-bold text-black">Đơn hàng #{{ $order->order_code }}</h3>
                                    <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-black text-white border border-black" style="border-radius:0;">
                                    Đã hoàn thành
                                </span>
                            </div>
                            <div class="p-6 space-y-6">
                                @foreach($order->orderItems as $item)
                                    <div class="flex flex-col lg:flex-row gap-6 pb-6 border-b border-slate-200 last:border-b-0 last:pb-0">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $item->book->image_url ?? 'https://via.placeholder.com/120x160' }}"
                                                 alt="{{ $item->book->name }}"
                                                 class="w-24 h-32 object-cover rounded-lg shadow-sm">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-medium text-slate-900 mb-3">{{ $item->book->name }}</h4>
                                            <p class="text-sm text-slate-600"><span class="font-medium">Tác giả:</span> {{ $item->book->author ?? 'N/A' }}</p>
                                            <p class="text-sm text-slate-600"><span class="font-medium">Nhà xuất bản:</span> {{ $item->book->publisher ?? 'N/A' }}</p>
                                            <p class="text-sm text-slate-600">
                                                <span class="font-medium">Còn {{ $item->review_deadline }} ngày để đánh giá</span>
                                            </p>
                                            <p class="text-sm text-slate-600">
                                                    <span class="font-medium">Số lượng:</span> {{ $item->quantity }}
                                                </p>
                                                <p class="text-sm text-slate-600">
                                                    <span class="font-medium">Giá:</span>
                                                    <span class="text-red-600 font-semibold">{{ number_format($item->price, 0, ',', '.') }} đ</span>
                                                </p>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('account.order.details', $order->id) }}" class="text-blue-500">Xem chi tiết</a>
                                            <form action="{{ route('account.review.form', ['order_id' => $order->id, 'book_id' => $item->book_id]) }}" method="GET">
                                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg">Đánh giá</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">Bạn chưa có đơn hàng nào chưa được đánh giá.</p>
                    @endforelse
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-black mt-8">Đã đánh giá</h3>
                    @forelse($orders->where('reviewed', true)->sortBy('completed_at') as $order)
                        <div class="bg-white border border-black shadow transition hover:shadow-lg" style="border-radius:0;">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-gray-50 border-b border-black">
                                <div>
                                    <h3 class="text-lg font-bold text-black">Đơn hàng #{{ $order->order_code }}</h3>
                                    <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-black text-white border border-black" style="border-radius:0;">
                                    Đã hoàn thành
                                </span>
                            </div>
                            <div class="p-6 space-y-6">
                                @foreach($order->orderItems as $item)
                                    <div class="flex flex-col lg:flex-row gap-6 pb-6 border-b border-slate-200 last:border-b-0 last:pb-0">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $item->book->image_url ?? 'https://via.placeholder.com/120x160' }}"
                                                 alt="{{ $item->book->name }}"
                                                 class="w-24 h-32 object-cover rounded-lg shadow-sm">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-medium text-slate-900 mb-3">{{ $item->book->name }}</h4>
                                            <div class="space-y-1">
                                                <p class="text-sm text-slate-600">
                                                    <span class="font-medium">Số lượng:</span> {{ $item->quantity }}
                                                </p>
                                                <p class="text-sm text-slate-600">
                                                    <span class="font-medium">Giá:</span>
                                                    <span class="text-red-600 font-semibold">{{ number_format($item->price, 0, ',', '.') }} đ</span>
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Review Section -->
                                        @php
                                            $review = $order->reviews()->withTrashed()->where('book_id', $item->book_id)->first();
                                        @endphp
                                        <div class="lg:w-96">
                                            <!-- Review details here -->
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">Bạn chưa đánh giá đơn hàng nào.</p>
                    @endforelse
                </div>
            @else
                <!-- Default display for types 2 and 3 (Chưa đánh giá and Đã đánh giá) -->
                @forelse($orders as $order)
                    <div class="bg-white border border-black shadow transition hover:shadow-lg" style="border-radius:0;">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 bg-gray-50 border-b border-black">
                            <div>
                                <h3 class="text-lg font-bold text-black">Đơn hàng #{{ $order->order_code }}</h3>
                                <p class="text-sm text-gray-600">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-black text-white border border-black" style="border-radius:0;">
                                Đã hoàn thành
                            </span>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach($order->orderItems as $item)
                                <div class="flex flex-col lg:flex-row gap-6 pb-6 border-b border-slate-200 last:border-b-0 last:pb-0">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $item->book->image_url ?? 'https://via.placeholder.com/120x160' }}"
                                             alt="{{ $item->book->name }}"
                                             class="w-24 h-32 object-cover rounded-lg shadow-sm">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-medium text-slate-900 mb-3">{{ $item->book->name }}</h4>
                                        <div class="space-y-1">
                                            <p class="text-sm text-slate-600">
                                                <span class="font-medium">Số lượng:</span> {{ $item->quantity }}
                                            </p>
                                            <p class="text-sm text-slate-600">
                                                <span class="font-medium">Giá:</span>
                                                <span class="text-red-600 font-semibold">{{ number_format($item->price, 0, ',', '.') }} đ</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
            @endif

            @if($orders->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
