<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang tài khoản')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />

    
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


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
</body>

</html>
@push('scripts')
<script>
    $(document).ready(function () {
        // Cấu hình toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        $('.wishlistForm').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let heartIcon = form.find('i.fa-heart');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        // Đổi icon trái tim thành màu đỏ và solid
                        heartIcon.removeClass('far').addClass('fas text-red-500');
                        toastr.success(response.message || 'Đã thêm vào danh sách yêu thích!');
                    } else {
                        toastr.warning(response.message || 'Không thể thêm vào wishlist');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 401) {
                        toastr.error('Bạn cần đăng nhập để thực hiện chức năng này');
                    } else {
                        toastr.error('Đã có lỗi xảy ra, vui lòng thử lại');
                    }
                }
            });
        });
    });
</script>
@endpush
<script src="{{ asset('js/wishlist.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
