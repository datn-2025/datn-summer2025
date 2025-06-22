<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ get_setting() ? get_setting()->name_website : 'BookBee' }} - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('storage/' . (get_setting() ? get_setting()->favicon : 'default_favicon.ico')) }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>

    <!-- IntersectionObserver polyfill -->
    <script>
        if (!('IntersectionObserver' in window)) {
            document.write('<script src="https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver"><\/script>');
        }
    </script>

    <!-- Google Fonts Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN (for nav effects) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .adidas-nav {
        font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      }
      .adidas-btn {
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
      }
      .adidas-btn:hover {
        transform: scale(1.05);
      }
      .adidas-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
      }
      .adidas-btn:hover::before {
        left: 100%;
      }
      .adidas-gradient-text {
        background: linear-gradient(45deg, #000000, #767677, #000000);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
    </style>
</head>

<body style="margin:0; min-height:100vh;">
    @include('layouts.partials.navbar')
    @yield('content')

    {!! Toastr::message() !!}


    <!-- jQuery (required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @stack('scripts')
    @include('layouts.partials.footer')
    <script>
       $(document).ready(function() {
    // Lấy tỉnh thành từ GHN API
    $.get('/ghn/provinces', function(response) {
        if (response.success) {
            response.data.forEach(function(province) {
                $("#tinh").append(`<option value="${province.ProvinceID}">${province.ProvinceName}</option>`);
            });
        }
    }).fail(function() {
        console.error('Không thể lấy danh sách tỉnh/thành phố');
    });

    // Xử lý khi chọn tỉnh
    $("#tinh").change(function() {
        const provinceId = $(this).val();
        const provinceName = $(this).find("option:selected").text();
        $("#ten_tinh").val(provinceName);
        $("#province_id").val(provinceId);
        
        // Reset quận/huyện và phường/xã
        $("#quan").html('<option value="">Chọn Quận/Huyện</option>');
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        $("#shipping-info").hide();
        
        if (provinceId) {
            // Lấy quận/huyện từ GHN API
            $.post('/ghn/districts', {
                province_id: provinceId,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(response) {
                if (response.success) {
                    response.data.forEach(function(district) {
                        $("#quan").append(`<option value="${district.DistrictID}">${district.DistrictName}</option>`);
                    });
                }
            }).fail(function() {
                console.error('Không thể lấy danh sách quận/huyện');
            });
        }
    });

    // Xử lý khi chọn quận
    $("#quan").change(function() {
        const districtId = $(this).val();
        const districtName = $(this).find("option:selected").text();
        $("#ten_quan").val(districtName);
        $("#ghn_district_id").val(districtId);
        
        // Reset phường/xã
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        $("#shipping-info").hide();
        
        if (districtId) {
            // Lấy phường/xã từ GHN API
            $.post('/ghn/wards', {
                district_id: districtId,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(response) {
                if (response.success) {
                    response.data.forEach(function(ward) {
                        $("#phuong").append(`<option value="${ward.WardCode}">${ward.WardName}</option>`);
                    });
                }
            }).fail(function() {
                console.error('Không thể lấy danh sách phường/xã');
            });
        }
    });

    // Xử lý khi chọn phường
    $("#phuong").change(function() {
        const wardCode = $(this).val();
        const wardName = $(this).find("option:selected").text();
        $("#ten_phuong").val(wardName);
        $("#ward_code").val(wardCode);
        
        // Tính phí vận chuyển và thời gian giao hàng
        if (wardCode && $("#ghn_district_id").val()) {
            calculateShippingInfo();
        }
    });
    
    // Hàm tính phí vận chuyển và thời gian giao hàng
    function calculateShippingInfo() {
        const districtId = $("#ghn_district_id").val();
        const wardCode = $("#ward_code").val();
        
        if (!districtId || !wardCode) return;
        
        // Tính phí vận chuyển
        $.post('/ghn/shipping-fee', {
            to_district_id: districtId,
            to_ward_code: wardCode,
            weight: 200, // Trọng lượng mặc định (gram)
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(response) {
            if (response.success) {
                const shippingFee = response.data.total;
                $("#ghn-shipping-fee").text(new Intl.NumberFormat('vi-VN').format(shippingFee) + 'đ');
                
                // Cập nhật phí vận chuyển trong form
                $("#form_hidden_shipping_fee").val(shippingFee);
                $("#shipping-fee").text(new Intl.NumberFormat('vi-VN').format(shippingFee) + 'đ');
                
                // Cập nhật tổng tiền
                updateTotal();
                
                $("#shipping-info").show();
            }
        }).fail(function() {
            console.error('Không thể tính phí vận chuyển');
        });
        
        // Tính thời gian giao hàng dự kiến
        $.post('/ghn/delivery-time', {
            to_district_id: districtId,
            to_ward_code: wardCode,
            _token: $('meta[name="csrf-token"]').attr('content')
        }, function(response) {
            if (response.success) {
                const deliveryTime = new Date(response.data.leadtime * 1000);
                const options = { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                $("#expected-delivery").text(deliveryTime.toLocaleDateString('vi-VN', options));
            }
        }).fail(function() {
            console.error('Không thể lấy thời gian giao hàng dự kiến');
        });
    }
});
    </script>
</body>

</html>