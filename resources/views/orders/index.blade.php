@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Đơn hàng của tôi</h1>

    @if($orders->isEmpty())
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <p class="text-gray-600">Bạn chưa có đơn hàng nào</p>
        <a href="{{ route('books.index') }}" class="inline-block mt-4 text-blue-500 hover:text-blue-600">
            Tiếp tục mua sắm
        </a>
    </div>
    @else
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mã đơn hàng
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ngày đặt
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tổng tiền
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Trạng thái
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Thanh toán
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            #{{ $order->order_code }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ number_format($order->total_amount) }} VNĐ
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($order->orderStatus->name === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->orderStatus->name === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->orderStatus->name === 'completed') bg-green-100 text-green-800
                            @elseif($order->orderStatus->name === 'cancelled') bg-red-100 text-red-800
                            @endif">
                            {{ $order->orderStatus->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($order->paymentStatus->name === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->paymentStatus->name === 'paid') bg-green-100 text-green-800
                            @elseif($order->paymentStatus->name === 'failed') bg-red-100 text-red-800
                            @endif">
                            {{ $order->paymentStatus->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900">
                            Chi tiết
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
