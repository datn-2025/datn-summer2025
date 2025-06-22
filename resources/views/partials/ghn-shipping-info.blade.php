<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
    <h4 class="font-semibold text-blue-800 mb-3">
        <i class="fas fa-shipping-fast mr-2"></i>
        Thông tin vận chuyển
    </h4>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Phí vận chuyển -->
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Phí vận chuyển:</span>
            <span class="font-semibold text-red-600">
                {{ number_format($order->shipping_fee, 0, ',', '.') }}đ
            </span>
        </div>
        
        <!-- Dịch vụ GHN -->
        @if($order->ghn_shipping_info && isset($order->ghn_shipping_info['service_name']))
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Dịch vụ:</span>
            <span class="font-medium">{{ $order->ghn_shipping_info['service_name'] }}</span>
        </div>
        @endif
        
        <!-- Ngày giao dự kiến -->
        @if($order->expected_delivery_time)
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Ngày giao dự kiến:</span>
            <span class="font-semibold text-green-600">
                {{ $order->expected_delivery_time->format('d/m/Y') }}
            </span>
        </div>
        @endif
        
        <!-- Mã đơn hàng GHN -->
        @if($order->ghn_order_code)
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Mã vận đơn GHN:</span>
            <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                {{ $order->ghn_order_code }}
            </span>
        </div>
        @endif
    </div>
    
    <!-- Trạng thái vận chuyển -->
    @if($order->ghn_order_code)
    <div class="mt-4 pt-4 border-t border-blue-200">
        <div class="flex items-center justify-between">
            <span class="text-gray-600">Trạng thái vận chuyển:</span>
            <button 
                onclick="trackGhnOrder('{{ $order->ghn_order_code }}')"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200"
            >
                <i class="fas fa-search mr-2"></i>
                Tra cứu vận đơn
            </button>
        </div>
    </div>
    @endif
</div>

<script>
function trackGhnOrder(orderCode) {
    // Mở tab mới để tra cứu vận đơn GHN
    const trackingUrl = `https://tracking.ghn.dev/?order_code=${orderCode}`;
    window.open(trackingUrl, '_blank');
}
</script>
