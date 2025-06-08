<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title', 'Trang tài khoản')</title>

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
    // Lấy tỉnh thành
    $.getJSON('https://provinces.open-api.vn/api/p/', function(provinces) {
        provinces.forEach(function(province) {
            $("#tinh").append(`<option value="${province.code}">${province.name}</option>`);
        });
    });

    // Xử lý khi chọn tỉnh
    $("#tinh").change(function() {
        const provinceCode = $(this).val();
        $("#ten_tinh").val($(this).find("option:selected").text());
        
        // Lấy quận/huyện
        $.getJSON(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`, function(provinceData) {
            $("#quan").html('<option value="">Chọn Quận/Huyện</option>');
            provinceData.districts.forEach(function(district) {
                $("#quan").append(`<option value="${district.code}">${district.name}</option>`);
            });
        });
    });

    // Xử lý khi chọn quận
    $("#quan").change(function() {
        const districtCode = $(this).val();
        $("#ten_quan").val($(this).find("option:selected").text());
        
        // Lấy phường/xã
        $.getJSON(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`, function(districtData) {
            $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
            districtData.wards.forEach(function(ward) {
                $("#phuong").append(`<option value="${ward.code}">${ward.name}</option>`);
            });
        });
    });

    // Xử lý khi chọn phường
    $("#phuong").change(function() {
        $("#ten_phuong").val($(this).find("option:selected").text());
    });
});
    </script>
</body>

</html>