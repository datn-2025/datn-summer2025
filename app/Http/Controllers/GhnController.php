<?php

namespace App\Http\Controllers;

use App\Services\GhnService;
use App\Services\AddressGHNService;
use App\Services\WeightCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GhnController extends Controller
{
    protected $ghnService;
    protected $addressGHNService;
    protected $weightCalculatorService;

    public function __construct(
        GhnService $ghnService, 
        AddressGHNService $addressGHNService,
        WeightCalculatorService $weightCalculatorService
    ) {
        $this->ghnService = $ghnService;
        $this->addressGHNService = $addressGHNService;
        $this->weightCalculatorService = $weightCalculatorService;
    }

    /**
     * Lấy danh sách tỉnh/thành phố
     */
    public function getProvinces()
    {
        $provinces = $this->addressGHNService->getProvinces();
        
        if ($provinces && isset($provinces['data'])) {
            return response()->json([
                'success' => true,
                'data' => $provinces['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy danh sách tỉnh/thành phố'
        ], 500);
    }    /**
     * Lấy danh sách quận/huyện theo tỉnh
     */
    public function getDistricts(Request $request)
    {
        $request->validate([
            'province_id' => 'required|integer'
        ]);

        // Lấy province_id từ JSON body hoặc query parameter
        $provinceId = $request->input('province_id');
        
        // Đảm bảo province_id là integer
        $provinceId = (int) $provinceId;

        $districts = $this->addressGHNService->getDistricts($provinceId);
        
        if ($districts && isset($districts['data'])) {
            return response()->json([
                'success' => true,
                'data' => $districts['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy danh sách quận/huyện'
        ], 500);
    }    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards(Request $request)
    {
        $request->validate([
            'district_id' => 'required|integer'
        ]);

        // Lấy district_id từ JSON body hoặc query parameter
        $districtId = $request->input('district_id');
        
        // Đảm bảo district_id là integer
        $districtId = (int) $districtId;

        $wards = $this->addressGHNService->getWards($districtId);
        
        if ($wards && isset($wards['data'])) {
            return response()->json([
                'success' => true,
                'data' => $wards['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy danh sách phường/xã'
        ], 500);
    }    /**
     * Tính phí vận chuyển
     */
    public function calculateShippingFee(Request $request)
    {
        $request->validate([
            'to_district_id' => 'required|integer',
            'to_ward_code' => 'required|string',
            'weight' => 'nullable|integer|min:1',
            'length' => 'nullable|integer|min:1',
            'width' => 'nullable|integer|min:1',
            'height' => 'nullable|integer|min:1',
            'insurance_value' => 'nullable|integer|min:0'
        ]);

        // Địa chỉ shop mặc định (có thể lấy từ database hoặc config)
        $fromDistrictId = config('ghn.from_district_id');
        $fromWardCode = config('ghn.from_ward_code');

        // Tính cân nặng từ giỏ hàng nếu không được cung cấp
        $weight = $request->input('weight');
        if (!$weight) {
            $weight = $this->weightCalculatorService->calculateCartWeight();
            // Nếu không có sách vật lý nào, trả về lỗi
            if ($weight <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng chỉ có sách điện tử, không cần giao hàng'
                ]);
            }
        }

        $data = [
            'from_district_id' => (int) $fromDistrictId,
            'from_ward_code' => (string) $fromWardCode,
            'to_district_id' => (int) $request->input('to_district_id'),
            'to_ward_code' => (string) $request->input('to_ward_code'),
            'weight' => (int) $weight,
            'length' => (int) ($request->input('length') ?? config('ghn.default_length')),
            'width' => (int) ($request->input('width') ?? config('ghn.default_width')),
            'height' => (int) ($request->input('height') ?? config('ghn.default_height')),
            'insurance_value' => (int) ($request->input('insurance_value') ?? 0)
        ];

        $result = $this->ghnService->calculateShippingFee($data);
        
        if ($result && isset($result['data'])) {
            return response()->json([
                'success' => true,
                'data' => array_merge($result['data'], [
                    'calculated_weight' => $weight
                ])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể tính phí vận chuyển'
        ], 500);
    }/**
     * Lấy thời gian giao hàng dự kiến
     */
    public function getExpectedDeliveryTime(Request $request)
    {
        $request->validate([
            'to_district_id' => 'required|integer',
            'to_ward_code' => 'required|string',
            'service_id' => 'nullable|integer'
        ]);

        // Địa chỉ shop mặc định
        $fromDistrictId = config('ghn.from_district_id');
        $fromWardCode = config('ghn.from_ward_code');

        $data = [
            'from_district_id' => (int) $fromDistrictId,
            'from_ward_code' => (string) $fromWardCode,
            'to_district_id' => (int) $request->input('to_district_id'),
            'to_ward_code' => (string) $request->input('to_ward_code'),
            'service_id' => $request->input('service_id') ? (int) $request->input('service_id') : null
        ];

        $result = $this->ghnService->getExpectedDeliveryTime($data);
        
        if ($result && isset($result['data'])) {
            return response()->json([
                'success' => true,
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy thời gian giao hàng dự kiến'
        ], 500);
    }    /**
     * Lấy danh sách dịch vụ vận chuyển
     */
    public function getServices(Request $request)
    {
        $request->validate([
            'to_district_id' => 'required|integer'
        ]);

        // Địa chỉ shop mặc định
        $fromDistrictId = config('ghn.from_district_id');

        $data = [
            'from_district_id' => (int) $fromDistrictId,
            'to_district_id' => (int) $request->input('to_district_id')
        ];

        $result = $this->ghnService->getServices($data);
        
        if ($result && isset($result['data'])) {
            return response()->json([
                'success' => true,
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không thể lấy danh sách dịch vụ vận chuyển'
        ], 500);
    }    /**
     * Tính toán đầy đủ thông tin vận chuyển (phí + thời gian giao hàng)
     */
    public function calculateFullShippingInfo(Request $request)
    {
        $request->validate([
            'to_district_id' => 'required|integer',
            'to_ward_code' => 'required|string',
            'weight' => 'nullable|integer|min:1',
            'length' => 'nullable|integer|min:1',
            'width' => 'nullable|integer|min:1',
            'height' => 'nullable|integer|min:1',
            'insurance_value' => 'nullable|integer|min:0'
        ]);

        // Địa chỉ shop mặc định
        $fromDistrictId = config('ghn.from_district_id');
        $fromWardCode = config('ghn.from_ward_code');

        // Tính cân nặng từ giỏ hàng nếu không được cung cấp
        $weight = $request->input('weight');
        if (!$weight) {
            $weight = $this->weightCalculatorService->calculateCartWeight();
            // Nếu không có sách vật lý nào, trả về thông báo
            if ($weight <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng chỉ có sách điện tử, không cần giao hàng',
                    'no_shipping_needed' => true
                ]);
            }
        }

        $data = [
            'from_district_id' => (int) $fromDistrictId,
            'from_ward_code' => (string) $fromWardCode,
            'to_district_id' => (int) $request->input('to_district_id'),
            'to_ward_code' => (string) $request->input('to_ward_code'),
            'weight' => (int) $weight,
            'length' => (int) ($request->input('length') ?? config('ghn.default_length')),
            'width' => (int) ($request->input('width') ?? config('ghn.default_width')),
            'height' => (int) ($request->input('height') ?? config('ghn.default_height')),
            'insurance_value' => (int) ($request->input('insurance_value') ?? 0)
        ];

        try {
            // Tính phí vận chuyển
            $shippingResult = $this->ghnService->calculateShippingFee($data);
            
            if (!$shippingResult || !isset($shippingResult['data'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể tính phí vận chuyển'
                ]);
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

            // Lưu service_id vào session để sử dụng khi tạo đơn hàng
            session(['ghn_service_id_' . $request->input('to_district_id') . '_' . $request->input('to_ward_code') => $serviceId]);

            return response()->json([
                'success' => true,
                'data' => [
                    'shipping_fee' => $shippingFee,
                    'service_id' => $serviceId,
                    'calculated_weight' => $weight,
                    'expected_delivery_time' => $expectedDeliveryTime ? $expectedDeliveryTime->format('Y-m-d H:i:s') : null,
                    'expected_delivery_date' => $expectedDeliveryTime ? $expectedDeliveryTime->format('d/m/Y') : null,
                    'service_name' => $shippingResult['data']['service_name'] ?? 'Giao hàng tiêu chuẩn'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Calculate full shipping info error', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính toán thông tin vận chuyển'
            ]);
        }
    }
}
