@extends('layouts.backend')

@section('title', 'Thêm Voucher Mới')
@section('breadcrumb-parent', 'Voucher')
@section('breadcrumb-child', 'Thêm mới')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thêm Voucher Mới</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5>Có lỗi xảy ra:</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.vouchers.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Mã Voucher <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                           id="code" name="code" value="{{ old('code') }}" required>
                                    @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <input type="text" class="form-control @error('description') is-invalid @enderror"
                                           id="description" name="description" value="{{ old('description') }}">
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount_percent">Phần trăm giảm (%) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('discount_percent') is-invalid @enderror"
                                           id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}"
                                           min="0" max="100" step="0.01" required>
                                    @error('discount_percent')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="max_discount">Giảm tối đa (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_discount') is-invalid @enderror"
                                           id="max_discount" name="max_discount" value="{{ old('max_discount') }}" min="0" step="0.01" required>
                                    @error('max_discount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="min_order_value">Đơn tối thiểu (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('min_order_value') is-invalid @enderror"
                                           id="min_order_value" name="min_order_value" value="{{ old('min_order_value') }}" min="0" step="0.01" required>
                                    @error('min_order_value')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quantity">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                                    @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="valid_from">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('valid_from') is-invalid @enderror"
                                           id="valid_from" name="valid_from" value="{{ old('valid_from') }}" required>
                                    @error('valid_from')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="valid_to">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('valid_to') is-invalid @enderror"
                                           id="valid_to" name="valid_to" value="{{ old('valid_to') }}" required>
                                    @error('valid_to')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="condition_type">Loại điều kiện áp dụng <span class="text-danger">*</span></label>
                                    <select class="form-control @error('condition_type') is-invalid @enderror"
                                            id="condition_type" name="condition_type" required>
                                        <option value="">-- Chọn --</option>
                                        <option value="all" {{ old('condition_type') == 'all' ? 'selected' : '' }}>Tất cả sản phẩm</option>
                                        <option value="category" {{ old('condition_type') == 'category' ? 'selected' : '' }}>Theo danh mục</option>
                                        <option value="author" {{ old('condition_type') == 'author' ? 'selected' : '' }}>Theo tác giả</option>
                                        <option value="brand" {{ old('condition_type') == 'brand' ? 'selected' : '' }}>Theo thương hiệu</option>
                                        <option value="book" {{ old('condition_type') == 'book' ? 'selected' : '' }}>Theo sách</option>
                                    </select>
                                    @error('condition_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Thêm container cho danh sách đối tượng -->
                                <div id="condition_options_container" style="display: none;">
                                    <div class="form-group">
                                        <label>Chọn đối tượng áp dụng <span class="text-danger">*</span></label>
                                        <div id="condition_options_list" class="mt-2">
                                            <!-- Danh sách đối tượng sẽ được thêm vào đây bởi JavaScript -->
                                        </div>
                                        <div id="condition_error" class="invalid-feedback" style="display: none;">
                                            Vui lòng chọn ít nhất một đối tượng áp dụng
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Lưu</button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Mã JavaScript cho trang create
    console.log("Script cho trang tạo voucher đang chạy.");

    $(document).ready(function() {
        // Xử lý alert messages
        $('.alert').each(function() {
            $(this).delay(5000).fadeOut(500);
        });

        // Xử lý nút đóng alert
        $('.alert .close').on('click', function() {
            $(this).closest('.alert').fadeOut(500);
        });

        // Code JavaScript hiện tại
        console.log("Document is ready, script is running.");

        // Thêm validation trước khi submit form
        $('form').on('submit', function(e) {
            var conditionType = $('#condition_type').val();
            if (conditionType !== 'all' && conditionType !== '') {
                var selectedOptions = $('input[name="condition_ids[]"]:checked').length;
                if (selectedOptions === 0) {
                    e.preventDefault();
                    $('#condition_error').show();
                    return false;
                }
            }
        });

        $('#condition_type').on('change', function() {
            var selectedCondition = $(this).val();
            $('#condition_error').hide();

            if (selectedCondition === 'book' || selectedCondition === 'category' || selectedCondition === 'author' || selectedCondition === 'brand') {
                $('#condition_options_container').show();

                $.ajax({
                    url: '{{ route("admin.vouchers.getConditionOptions") }}',
                    method: 'GET',
                    data: { condition_type: selectedCondition },
                    success: function(response) {
                        var optionsHtml = '';
                        if (response.options && response.options.length > 0) {
                            response.options.forEach(function(option) {
                                optionsHtml += `<div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="condition_ids[]"
                                           value="${option.id}"
                                           id="option_${option.id}">
                                    <label class="form-check-label" for="option_${option.id}">
                                        ${option.name}
                                    </label>
                                </div>`;
                            });
                        } else {
                            optionsHtml = '<p>Không tìm thấy đối tượng nào.</p>';
                        }
                        $('#condition_options_list').html(optionsHtml);
                    },
                    error: function(xhr, status, error) {
                        $('#condition_options_list').html('<p>Lỗi khi tải danh sách.</p>');
                    }
                });
            } else {
                $('#condition_options_container').hide();
                $('#condition_options_list').empty();
            }
        });

        // Kích hoạt sự kiện change khi tải trang nếu có giá trị mặc định
        if($('#condition_type').val() !== '') {
            $('#condition_type').trigger('change');
        }
    });
</script>
@endsection
