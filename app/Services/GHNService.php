<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GhnService
{
    private $apiUrl;
    private $apiKey;
    private $shopId;

    public function __construct()
    {
        $this->apiUrl = config('ghn.api_url');
        $this->apiKey = config('ghn.api_key');
        $this->shopId = config('ghn.shop_id');
    }

    /**
     * Lấy danh sách tỉnh/thành phố
     */
    public function getProvinces()
    {
        try {
            $response = Http::withHeaders([
                'Token' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->get($this->apiUrl . '/master-data/province');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GHN get provinces failed', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('GHN get provinces error', ['error' => $e->getMessage()]);
            return null;
        }
    }    /**
     * Lấy danh sách quận/huyện theo tỉnh
     */
    public function getDistricts($provinceId)
    {
        try {
            $response = Http::withHeaders([
                'Token' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/master-data/district', [
                'province_id' => (int) $provinceId
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GHN get districts failed', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('GHN get districts error', ['error' => $e->getMessage()]);
            return null;
        }
    }    /**
     * Lấy danh sách phường/xã theo quận/huyện
     */
    public function getWards($districtId)
    {
        try {
            $response = Http::withHeaders([
                'Token' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/master-data/ward', [
                'district_id' => (int) $districtId
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GHN get wards failed', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('GHN get wards error', ['error' => $e->getMessage()]);
            return null;
        }
    }/**
     * Tính phí vận chuyển
     */    public function calculateShippingFee($data)
    {
        try {
            Log::info('GHN calculateShippingFee input data', $data);
              // Lấy service_id nếu chưa có
            $serviceId = $data['service_id'] ?? null;
            if (!$serviceId) {
                $servicesData = [
                    'from_district_id' => $data['from_district_id'],
                    'to_district_id' => $data['to_district_id']
                ];
                Log::info('Getting services for districts', $servicesData);
                $services = $this->getServices($servicesData);
                Log::info('Services response', ['services' => $services]);
                
                if ($services && isset($services['data']) && !empty($services['data'])) {
                    $serviceId = $services['data'][0]['service_id'];
                    Log::info('Selected service_id', ['service_id' => $serviceId]);
                } else {
                    Log::warning('No services available or services call failed');
                }
            }            $payload = [
                'service_id' => $serviceId,
                'insurance_value' => (int) ($data['insurance_value'] ?? 0),
                'coupon' => null,
                'from_district_id' => (int) $data['from_district_id'],
                'from_ward_code' => (string) $data['from_ward_code'],
                'to_district_id' => (int) $data['to_district_id'],
                'to_ward_code' => (string) $data['to_ward_code'],
                'height' => (int) ($data['height'] ?? 10),
                'length' => (int) ($data['length'] ?? 20),
                'weight' => (int) ($data['weight'] ?? 200),
                'width' => (int) ($data['width'] ?? 15),
            ];

            Log::info('GHN calculateShippingFee payload', $payload);$response = Http::withHeaders([
                'Token' => $this->apiKey,
                'ShopId' => (int) $this->shopId,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/v2/shipping-order/fee', $payload);            if ($response->successful()) {
                $result = $response->json();
                Log::info('GHN calculateShippingFee success response', $result);
                
                // Thêm service_id vào kết quả để sử dụng sau này
                if (isset($result['data'])) {
                    $result['data']['service_id'] = $serviceId;
                }
                return $result;
            }

            Log::error('GHN calculate shipping fee failed', [
                'payload' => $payload,
                'response' => $response->body()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('GHN calculate shipping fee error', ['error' => $e->getMessage()]);
            return null;
        }
    }    /**
     * Lấy thời gian giao hàng dự kiến
     */
    public function getExpectedDeliveryTime($data)
    {
        try {
            $payload = [
                'from_district_id' => (int) $data['from_district_id'],
                'from_ward_code' => (string) $data['from_ward_code'],
                'to_district_id' => (int) $data['to_district_id'],
                'to_ward_code' => (string) $data['to_ward_code'],
                'service_id' => $data['service_id'] ?? null
            ];

            $response = Http::withHeaders([
                'Token' => $this->apiKey,
                'ShopId' => (int) $this->shopId,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/v2/shipping-order/leadtime', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GHN get expected delivery time failed', [
                'payload' => $payload,
                'response' => $response->body()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('GHN get expected delivery time error', ['error' => $e->getMessage()]);
            return null;
        }
    }/**
     * Lấy danh sách dịch vụ vận chuyển
     */    public function getServices($data)
    {
        try {
            Log::info('GHN getServices input', $data);
            
            $payload = [
                'shop_id' => (int) $this->shopId,
                'from_district' => (int) $data['from_district_id'],
                'to_district' => (int) $data['to_district_id']
            ];
            
            Log::info('GHN getServices payload', $payload);
            
            $response = Http::withHeaders([
                'Token' => $this->apiKey,
                'ShopId' => (int) $this->shopId,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/v2/shipping-order/available-services', $payload);

            Log::info('GHN getServices response status', [
                'status' => $response->status(),
                'successful' => $response->successful()
            ]);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('GHN getServices success response', $result);
                return $result;
            }

            Log::error('GHN get services failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('GHN get services error', ['error' => $e->getMessage()]);
            return null;
        }
    }/**
     * Tạo đơn hàng giao hàng
     */
    public function createOrder($orderData)
    {
        try {
            $response = Http::withHeaders([
                'Token' => $this->apiKey,
                'ShopId' => (int) $this->shopId,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/v2/shipping-order/create', $orderData);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GHN create order failed', [
                'orderData' => $orderData,
                'response' => $response->body()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('GHN create order error', ['error' => $e->getMessage()]);
            return null;
        }
    }    /**
     * Hủy đơn hàng giao hàng
     */
    public function cancelOrder($orderCode)
    {
        try {
            $response = Http::withHeaders([
                'Token' => $this->apiKey,
                'ShopId' => (int) $this->shopId,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/v2/shipping-order/cancel', [
                'order_codes' => [$orderCode]
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GHN cancel order failed', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('GHN cancel order error', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
