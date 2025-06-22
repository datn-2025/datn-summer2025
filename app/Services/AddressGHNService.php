<?php

namespace App\Services;

use App\Models\Address;
use App\Services\GhnService;
use App\Services\WeightCalculatorService;
use Illuminate\Support\Facades\Cache;

class AddressGHNService
{
    protected $ghnService;
    protected $weightCalculatorService;

    public function __construct(GhnService $ghnService, WeightCalculatorService $weightCalculatorService)
    {
        $this->ghnService = $ghnService;
        $this->weightCalculatorService = $weightCalculatorService;
    }    /**
     * Tính phí vận chuyển và thời gian giao hàng cho địa chỉ
     */
    public function calculateShippingInfo($addressId, $weight = null)
    {
        $address = Address::find($addressId);
        
        if (!$address || !$address->district_id || !$address->ward_code) {
            return [
                'success' => false,
                'message' => 'Địa chỉ không có thông tin GHN'
            ];
        }

        // Tính cân nặng từ giỏ hàng nếu không được cung cấp
        if (!$weight) {
            $weight = $this->weightCalculatorService->calculateCartWeight();
            // Nếu không có sách vật lý nào, không cần giao hàng
            if ($weight <= 0) {
                return [
                    'success' => false,
                    'message' => 'Giỏ hàng chỉ có sách điện tử, không cần giao hàng',
                    'no_shipping_needed' => true
                ];
            }
        }

        // Cache key để tránh gọi API liên tục
        $cacheKey = "ghn_shipping_{$addressId}_{$weight}";
        
        return Cache::remember($cacheKey, config('ghn.cache_duration.shipping_info'), function () use ($address, $weight) {
            return $this->fetchShippingInfo($address, $weight);
        });
    }

    private function fetchShippingInfo($address, $weight)
    {        $data = [
            'from_district_id' => config('ghn.from_district_id'),
            'from_ward_code' => config('ghn.from_ward_code'),
            'to_district_id' => $address->district_id,
            'to_ward_code' => $address->ward_code,
            'weight' => $weight,
            'length' => config('ghn.default_length'),
            'width' => config('ghn.default_width'),
            'height' => config('ghn.default_height'),
            'insurance_value' => 0
        ];

        // Lấy phí vận chuyển
        $shippingResult = $this->ghnService->calculateShippingFee($data);
        
        if (!$shippingResult || !isset($shippingResult['data'])) {
            return [
                'success' => false,
                'message' => 'Không thể tính phí vận chuyển'
            ];
        }

        $serviceId = $shippingResult['data']['service_id'] ?? null;
        $shippingFee = $shippingResult['data']['total'] ?? 0;

        // Lấy thời gian giao hàng
        $deliveryData = array_merge($data, ['service_id' => $serviceId]);
        $deliveryResult = $this->ghnService->getExpectedDeliveryTime($deliveryData);

        $expectedDelivery = null;
        if ($deliveryResult && isset($deliveryResult['data']['leadtime'])) {
            $expectedDelivery = now()->addSeconds($deliveryResult['data']['leadtime']);
        }

        return [
            'success' => true,
            'data' => [
                'shipping_fee' => $shippingFee,
                'service_id' => $serviceId,
                'expected_delivery' => $expectedDelivery,
                'expected_delivery_timestamp' => $deliveryResult['data']['leadtime'] ?? null
            ]
        ];
    }

    /**
     * Lấy danh sách tỉnh/thành phố với cache
     */    public function getProvinces()
    {
        return Cache::remember('ghn_provinces', config('ghn.cache_duration.provinces'), function () {
            return $this->ghnService->getProvinces();
        });
    }

    /**
     * Lấy danh sách quận/huyện với cache
     */
    public function getDistricts($provinceId)
    {
        return Cache::remember("ghn_districts_{$provinceId}", config('ghn.cache_duration.districts'), function () use ($provinceId) {
            return $this->ghnService->getDistricts($provinceId);
        });
    }

    /**
     * Lấy danh sách phường/xã với cache
     */
    public function getWards($districtId)
    {
        return Cache::remember("ghn_wards_{$districtId}", config('ghn.cache_duration.wards'), function () use ($districtId) {
            return $this->ghnService->getWards($districtId);
        });
    }
}
