# Tích hợp API Giao Hàng Nhanh (GHN)

## Tổng quan

Dự án đã được tích hợp với API Giao Hàng Nhanh (GHN) để:
- Tính phí vận chuyển tự động
- Tính thời gian giao hàng dự kiến
- Tạo đơn hàng vận chuyển
- Tra cứu trạng thái vận đơn

## Cấu hình

### 1. File môi trường (.env)

```env
# Giao hàng nhanh - token
GHN_API_URL=https://dev-online-gateway.ghn.vn/shiip/public-api
GHN_API_KEY=your-ghn-api-key
GHN_SHOP_ID=your-shop-id
GHN_FROM_DISTRICT_ID=1454
GHN_FROM_WARD_CODE=21211
```

### 2. File cấu hình (config/ghn.php)

File này chứa tất cả cấu hình cho GHN bao gồm:
- URL API và thông tin xác thực
- Địa chỉ shop mặc định
- Kích thước và trọng lượng mặc định của sản phẩm
- Thời gian cache

## Các thành phần chính

### 1. Services

#### GhnService
- `app/Services/GhnService.php`
- Xử lý tất cả API calls đến GHN
- Methods:
  - `getProvinces()`: Lấy danh sách tỉnh/thành phố
  - `getDistricts($provinceId)`: Lấy danh sách quận/huyện
  - `getWards($districtId)`: Lấy danh sách phường/xã
  - `calculateShippingFee($data)`: Tính phí vận chuyển
  - `getExpectedDeliveryTime($data)`: Tính thời gian giao hàng
  - `createOrder($orderData)`: Tạo đơn hàng GHN

#### ShippingService
- `app/Services/ShippingService.php`
- Service layer cho việc xử lý vận chuyển
- Methods:
  - `calculateShippingInfo($addressId)`: Tính phí ship cho địa chỉ
  - `createGhnOrder($order)`: Tạo đơn hàng GHN
  - `cancelGhnOrder($ghnOrderCode)`: Hủy đơn hàng GHN

#### AddressGHNService
- `app/Services/AddressGHNService.php`
- Xử lý cache cho địa chỉ và thông tin vận chuyển

### 2. Controllers

#### GhnController
- `app/Http/Controllers/GhnController.php`
- Xử lý các API endpoints cho frontend
- Endpoints:
  - `GET /ghn/provinces`: Lấy danh sách tỉnh
  - `POST /ghn/districts`: Lấy danh sách quận/huyện
  - `POST /ghn/wards`: Lấy danh sách phường/xã
  - `POST /ghn/shipping-fee`: Tính phí vận chuyển
  - `POST /ghn/delivery-time`: Tính thời gian giao hàng
  - `POST /ghn/full-shipping-info`: Tính đầy đủ thông tin vận chuyển

### 3. Database

#### Migration files
- `2025_06_21_add_ghn_fields_to_orders_table.php`: Thêm trường GHN vào bảng orders
- `2025_06_21_add_ghn_fields_to_addresses_table.php`: Thêm trường GHN vào bảng addresses

#### Trường được thêm vào Orders
- `ghn_order_code`: Mã đơn hàng GHN
- `ghn_service_id`: ID dịch vụ GHN
- `expected_delivery_time`: Thời gian giao hàng dự kiến
- `ghn_shipping_info`: Thông tin vận chuyển (JSON)

#### Trường được thêm vào Addresses
- `province_id`: ID tỉnh/thành phố GHN
- `district_id`: ID quận/huyện GHN
- `ward_code`: Mã phường/xã GHN

### 4. Frontend JavaScript

#### ghn-shipping.js
- Xử lý tích hợp GHN trong trang checkout
- Auto-load provinces, districts, wards
- Tính phí vận chuyển real-time

#### address-manager.js
- Xử lý thêm/sửa địa chỉ với thông tin GHN
- Validate địa chỉ GHN

### 5. Middleware

#### GhnErrorHandler
- `app/Http/Middleware/GhnErrorHandler.php`
- Xử lý lỗi từ GHN API
- Log các lỗi để debug

## Cách sử dụng

### 1. Trong trang Checkout

```html
<!-- Include JavaScript files -->
<script src="{{ asset('js/ghn-shipping.js') }}"></script>
<script src="{{ asset('js/address-manager.js') }}"></script>

<!-- HTML elements cần thiết -->
<select id="province" name="province"></select>
<select id="district" name="district"></select>
<select id="ward" name="ward"></select>
<div id="shipping-info"></div>

<!-- Hidden inputs -->
<input type="hidden" name="shipping_fee_applied" />
<input type="hidden" name="ghn_service_id" />
```

### 2. Trong Controller

```php
// Tính phí vận chuyển
$shippingInfo = $this->shippingService->calculateShippingInfo($addressId);

// Tạo đơn hàng GHN
$result = $this->shippingService->createGhnOrder($order);
```

### 3. Trong View (hiển thị thông tin GHN)

```html
@include('partials.ghn-shipping-info', ['order' => $order])
```

## Commands

### Đồng bộ trạng thái đơn hàng GHN

```bash
# Đồng bộ tất cả đơn hàng
php artisan ghn:sync-orders

# Đồng bộ đơn hàng cụ thể
php artisan ghn:sync-orders --order_id=123

# Giới hạn số lượng đơn hàng
php artisan ghn:sync-orders --limit=100
```

## Test

### Test API GHN

```bash
# Test lấy danh sách tỉnh
curl -X GET http://your-domain/ghn/provinces

# Test tính phí vận chuyển
curl -X POST http://your-domain/ghn/full-shipping-info \
  -H "Content-Type: application/json" \
  -d '{"to_district_id": 1442, "to_ward_code": "21012"}'
```

## Troubleshooting

### 1. Lỗi API Key không hợp lệ
- Kiểm tra GHN_API_KEY trong file .env
- Đảm bảo API key đã được kích hoạt

### 2. Lỗi Shop ID không tồn tại
- Kiểm tra GHN_SHOP_ID trong file .env
- Đăng ký shop trên GHN nếu chưa có

### 3. Lỗi địa chỉ không hợp lệ
- Đảm bảo district_id và ward_code được lưu đúng
- Kiểm tra dữ liệu từ API GHN

### 4. Debug logs
```bash
# Xem logs GHN
tail -f storage/logs/laravel.log | grep GHN
```

## Tối ưu hiệu suất

### 1. Cache
- Provinces, districts, wards được cache 1 giờ
- Shipping info được cache 5 phút
- Có thể điều chỉnh trong config/ghn.php

### 2. Background jobs
- Tạo đơn hàng GHN có thể chuyển thành job để tránh block user
- Đồng bộ trạng thái có thể chạy bằng cron job

## Lưu ý bảo mật

- API key GHN phải được giữ bí mật
- Không expose API key trong frontend
- Validate tất cả input từ user trước khi gọi GHN API
- Rate limiting cho GHN endpoints

## Tài liệu tham khảo

- [GHN API Documentation](https://api.ghn.vn/home/docs/detail?id=78)
- [GHN Developer Portal](https://khachhang.giaohangnhanh.vn/)
