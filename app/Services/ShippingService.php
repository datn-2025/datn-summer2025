<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Services\GhnService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $ghnService;

    public function __construct(GhnService $ghnService)
    {
        $this->ghnService = $ghnService;
    }

    /**
     * Tính phí vận chuyển và thời gian giao hàng cho địa chỉ
     */
    public function calculateShippingInfo($addressId, $weight = null)
    {
        $address = Address::find($addressId);
        
        if (!$address || !$address->district_id || !$address->ward_code) {
            return [
                'success' => false,
                'message' => 'Địa chỉ không có thông tin GHN đầy đủ'
            ];
        }

        // Cache key để tránh gọi API liên tục
        $cacheKey = "shipping_info_{$addressId}_" . ($weight ?? config('ghn.default_weight'));
        
        return Cache::remember($cacheKey, config('ghn.cache_duration.shipping_info'), function () use ($address, $weight) {
            return $this->fetchShippingInfo($address, $weight);
        });
    }

    /**
     * Fetch shipping info từ GHN API
     */
    private function fetchShippingInfo($address, $weight)
    {
        $data = [
            'from_district_id' => config('ghn.from_district_id'),
            'from_ward_code' => config('ghn.from_ward_code'),
            'to_district_id' => $address->district_id,
            'to_ward_code' => $address->ward_code,
            'weight' => $weight ?? config('ghn.default_weight'),
            'length' => config('ghn.default_length'),
            'width' => config('ghn.default_width'),
            'height' => config('ghn.default_height'),
            'insurance_value' => 0
        ];

        try {
            // Tính phí vận chuyển
            $shippingResult = $this->ghnService->calculateShippingFee($data);
            
            if (!$shippingResult || !isset($shippingResult['data'])) {
                return [
                    'success' => false,
                    'message' => 'Không thể tính phí vận chuyển'
                ];
            }

            $serviceId = $shippingResult['data']['service_id'] ?? null;
            $shippingFee = $shippingResult['data']['total'] ?? 0;

            // Tính thời gian giao hàng dự kiến
            $deliveryData = array_merge($data, ['service_id' => $serviceId]);
            $deliveryResult = $this->ghnService->getExpectedDeliveryTime($deliveryData);

            $expectedDeliveryTime = null;
            if ($deliveryResult && isset($deliveryResult['data']['leadtime'])) {
                $expectedDeliveryTime = now()->addSeconds($deliveryResult['data']['leadtime']);
            }

            return [
                'success' => true,
                'data' => [
                    'shipping_fee' => $shippingFee,
                    'service_id' => $serviceId,
                    'expected_delivery_time' => $expectedDeliveryTime,
                    'expected_delivery_date' => $expectedDeliveryTime ? $expectedDeliveryTime->format('d/m/Y') : null,
                    'service_name' => $shippingResult['data']['service_name'] ?? 'Giao hàng tiêu chuẩn',
                    'ghn_shipping_info' => [
                        'service_id' => $serviceId,
                        'service_name' => $shippingResult['data']['service_name'] ?? null,
                        'from_district_id' => $data['from_district_id'],
                        'from_ward_code' => $data['from_ward_code'],
                        'to_district_id' => $data['to_district_id'],
                        'to_ward_code' => $data['to_ward_code'],
                        'weight' => $data['weight']
                    ]
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Shipping calculation error', [
                'error' => $e->getMessage(),
                'address_id' => $address->id,
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính toán phí vận chuyển'
            ];
        }
    }

    /**
     * Tạo đơn hàng GHN
     */
    public function createGhnOrder(Order $order)
    {
        $address = $order->address;
        if (!$address || !$address->district_id || !$address->ward_code) {
            return [
                'success' => false,
                'message' => 'Địa chỉ giao hàng không có thông tin GHN'
            ];
        }

        // Tính tổng trọng lượng từ các sản phẩm trong đơn hàng
        $totalWeight = $this->calculateOrderWeight($order);

        $orderData = [
            'to_name' => $order->recipient_name,
            'to_phone' => $order->recipient_phone,
            'to_address' => $address->address_detail,
            'to_ward_code' => $address->ward_code,
            'to_district_id' => $address->district_id,
            'cod_amount' => $order->paymentMethod && str_contains(strtolower($order->paymentMethod->name), 'khi nhận hàng') ? $order->total_amount : 0,
            'content' => 'Đơn hàng sách từ BookBee - ' . $order->order_code,
            'weight' => $totalWeight,
            'length' => config('ghn.default_length'),
            'width' => config('ghn.default_width'),
            'height' => config('ghn.default_height'),
            'service_id' => $order->ghn_service_id,
            'payment_type_id' => 1, // 1: Shop/Seller trả phí ship, 2: Buyer trả phí ship
            'note' => $order->note ?? '',
            'required_note' => 'CHOXEMHANGKHONGTHU', // CHOTHUHANG, CHOXEMHANGKHONGTHU, KHONGCHOXEMHANG
            'items' => $this->prepareOrderItems($order)
        ];

        try {
            $result = $this->ghnService->createOrder($orderData);
            
            if ($result && isset($result['data']['order_code'])) {
                // Cập nhật mã đơn hàng GHN vào order
                $order->update([
                    'ghn_order_code' => $result['data']['order_code']
                ]);

                return [
                    'success' => true,
                    'ghn_order_code' => $result['data']['order_code'],
                    'data' => $result['data']
                ];
            }

            return [
                'success' => false,
                'message' => 'Không thể tạo đơn hàng GHN'
            ];

        } catch (\Exception $e) {
            Log::error('Create GHN order error', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'order_data' => $orderData
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng GHN'
            ];
        }
    }

    /**
     * Chuẩn bị thông tin sản phẩm cho GHN
     */
    private function prepareOrderItems(Order $order)
    {
        $items = [];
        foreach ($order->orderItems as $item) {
            $items[] = [
                'name' => $item->book->title,
                'code' => $item->book->isbn ?? $item->book->id,
                'quantity' => $item->quantity,
                'price' => (int) $item->price,
                'length' => config('ghn.default_length'),
                'width' => config('ghn.default_width'),
                'height' => config('ghn.default_height'),
                'weight' => config('ghn.default_weight')
            ];
        }
        return $items;
    }

    /**
     * Tính tổng trọng lượng đơn hàng
     */
    private function calculateOrderWeight(Order $order)
    {
        $totalQuantity = $order->orderItems->sum('quantity');
        return $totalQuantity * config('ghn.default_weight');
    }

    /**
     * Hủy đơn hàng GHN
     */
    public function cancelGhnOrder($ghnOrderCode)
    {
        if (!$ghnOrderCode) {
            return [
                'success' => false,
                'message' => 'Không có mã đơn hàng GHN'
            ];
        }

        try {
            $result = $this->ghnService->cancelOrder(['order_codes' => [$ghnOrderCode]]);
            
            return [
                'success' => $result !== null,
                'data' => $result
            ];

        } catch (\Exception $e) {
            Log::error('Cancel GHN order error', [
                'error' => $e->getMessage(),
                'ghn_order_code' => $ghnOrderCode
            ]);

            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi hủy đơn hàng GHN'
            ];
        }
    }
}
