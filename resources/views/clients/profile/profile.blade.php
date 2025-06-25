@extends('layouts.account.layout')

@section('account_content')
<div class="bg-white border border-black shadow mb-8" style="border-radius: 0;">
    <div class="px-8 py-6 border-b border-black bg-black">
        <h1 class="text-2xl font-bold text-white uppercase tracking-wide">Tài Khoản Của Tôi</h1>
    </div>
    <div class="p-8">
        <div class="flex space-x-1 mb-8 border-b border-black">
            @foreach ([1 => 'Thông Tin Cá Nhân', 2 => 'Địa Chỉ', 3 => 'Đổi Mật Khẩu'] as $type => $label)
                <a href="{{ route('account.profile', ['type' => $type]) }}"
                   class="flex-1 text-center px-6 py-3 text-base font-semibold border-b-2 transition
                       {{ request('type', '1') == $type ? 'border-black text-black bg-white' : 'border-transparent text-gray-500 hover:text-black hover:bg-gray-100' }}"
                   style="border-radius: 0;">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="space-y-6">
            <!-- Profile Section -->
            @if(request('type', '1') == 1)
                <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Avatar Section -->
                        <div class="w-full md:w-1/4 text-center">
                            <div class="avatar">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6366f1&color=fff&size=200' }}" 
                                     alt="Avatar" class="w-32 h-32 object-cover rounded-full shadow-md mx-auto">
                                <div class="mt-3">
                                    <label for="avatar-input" class="btn-save w-full">
                                        <i class="fas fa-camera"></i> Chọn ảnh
                                    </label>
                                    <input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png" class="hidden">
                                </div>
                            </div>
                        </div>

                        <!-- User Info Form -->
                        <div class="w-full md:w-3/4">
                            <div class="mb-4">
                                <label class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                            </div>

                            <!-- Save Button -->
                            <button type="submit" class="btn btn-save w-full">
                                <i class="fas fa-save me-2"></i> Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </form>
            @elseif(request('type', '1') == 3) <!-- Change Password -->
                <form method="POST" action="{{ route('account.password.update') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-lock text-primary me-1"></i>
                            Mật khẩu hiện tại
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input id="current_password" type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   name="current_password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-key text-primary me-1"></i>
                            Mật khẩu mới
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input id="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-check-circle text-primary me-1"></i>
                            Xác nhận mật khẩu mới
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                            <input id="password_confirmation" type="password" 
                                   class="form-control" 
                                   name="password_confirmation" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('account.profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-key me-2"></i>Cập nhật mật khẩu
                        </button>
                    </div>
                </form>
            @elseif(request('type', '1') == 2) <!-- Address Management -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">
                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                        Quản lý địa chỉ
                    </h2>
                    <button class="btn btn-primary" onclick="openAddressModal()">
                        <i class="fas fa-plus me-2"></i>
                        Thêm địa chỉ mới
                    </button>
                </div>

                <!-- Address List -->
                <div id="addressList">
                    @if(isset($addresses) && $addresses->count() > 0)
                        @foreach($addresses as $address)
                            <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                                @if($address->is_default)
                                    <div class="default-badge">
                                        <i class="fas fa-star me-1"></i>
                                        Mặc định
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="mb-0 text-muted">
                                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                            {{ $address->address_detail }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->city }}
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        @if(!$address->is_default)
                                            <button class="btn btn-success btn-sm me-2" onclick="setDefaultAddress('{{ $address->id }}')">
                                                <i class="fas fa-star"></i>
                                                Đặt mặc định
                                            </button>
                                        @endif
                                        <button class="btn btn-warning btn-sm me-2" onclick="editAddress('{{ $address->id }}')">
                                            <i class="fas fa-edit"></i>
                                            Sửa
                                        </button>
                                        @if(!$address->is_default)
                                            <button class="btn btn-danger btn-sm" onclick="deleteAddress('{{ $address->id }}')">
                                                <i class="fas fa-trash"></i>
                                                Xóa
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4>Chưa có địa chỉ nào</h4>
                            <p>Thêm địa chỉ giao hàng để thuận tiện cho việc đặt hàng</p>
                            <button class="btn btn-primary" onclick="openAddressModal()">
                                <i class="fas fa-plus me-2"></i>
                                Thêm địa chỉ đầu tiên
                            </button>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalLabel">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    Thêm địa chỉ mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addressForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">                        
                        <div class="col-md-4">
                            <label for="tinh" class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" id="tinh" name="city_code" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                            </select>
                            <input type="hidden" id="ten_tinh" name="city">
                        </div>
                        <div class="col-md-4">
                            <label for="quan" class="form-label">Quận/Huyện</label>
                            <select class="form-select" id="quan" name="district_code" required>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                            <input type="hidden" id="ten_quan" name="district">
                        </div>
                        <div class="col-md-4">
                            <label for="phuong" class="form-label">Phường/Xã</label>
                            <select class="form-select" id="phuong" name="ward_code" required>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                            <input type="hidden" id="ten_phuong" name="ward">
                        </div>
                        <div class="col-12">
                            <label for="address_detail" class="form-label">Địa chỉ cụ thể</label>
                            <textarea class="form-control" id="address_detail" name="address_detail" rows="3" placeholder="Số nhà, tên đường..." required></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                                <label class="form-check-label" for="is_default">
                                    Đặt làm địa chỉ mặc định
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Lưu địa chỉ</span>
                        <div class="loading d-none"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let isEdit = false;
    let editId = null;

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
            const provinceName = $(this).find("option:selected").text();
            $("#ten_tinh").val(provinceName);
            
            // Reset quận và phường
            $("#quan").html('<option value="">Chọn Quận/Huyện</option>');
            $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
            $("#ten_quan").val('');
            $("#ten_phuong").val('');
            
            if (provinceCode) {
                // Lấy quận/huyện
                $.getJSON(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`, function(provinceData) {
                    provinceData.districts.forEach(function(district) {
                        $("#quan").append(`<option value="${district.code}">${district.name}</option>`);
                    });
                });
            }
        });

        // Xử lý khi chọn quận
        $("#quan").change(function() {
            const districtCode = $(this).val();
            const districtName = $(this).find("option:selected").text();
            $("#ten_quan").val(districtName);
            
            // Reset phường
            $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
            $("#ten_phuong").val('');
            
            if (districtCode) {
                // Lấy phường/xã
                $.getJSON(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`, function(districtData) {
                    districtData.wards.forEach(function(ward) {
                        $("#phuong").append(`<option value="${ward.code}">${ward.name}</option>`);
                    });
                });
            }
        });

        // Xử lý khi chọn phường
        $("#phuong").change(function() {
            const wardName = $(this).find("option:selected").text();
            $("#ten_phuong").val(wardName);
        });

        // Submit form
        $('#addressForm').on('submit', function(e) {
            e.preventDefault();
            
            // Validate that province/district/ward names are selected
            if (!$('#ten_tinh').val() || !$('#ten_quan').val() || !$('#ten_phuong').val()) {
                toastr.error('Vui lòng chọn đầy đủ Tỉnh/Thành phố, Quận/Huyện và Phường/Xã');
                return;
            }
            
            const $submitBtn = $('#submitBtn');
            const $btnText = $submitBtn.find('.btn-text');
            const $loading = $submitBtn.find('.loading');
            
            // Hiển thị loading
            $btnText.addClass('d-none');
            $loading.removeClass('d-none');
            $submitBtn.prop('disabled', true);
            
            // Chuẩn bị data
            const formData = new FormData(this);
            
            // Set URL và method based on edit mode
            const url = isEdit ? `/account/addresses/${editId}` : '{{ route("account.addresses.store") }}';
            if (isEdit) {
                formData.append('_method', 'PUT');
            }
            
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message || 'Lưu địa chỉ thành công!');
                    $('#addressModal').modal('hide');
                    location.reload(); // Reload trang để cập nhật danh sách
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        const message = xhr.responseJSON?.message || 'Có lỗi xảy ra. Vui lòng thử lại!';
                        toastr.error(message);
                    }
                },
                complete: function() {
                    // Ẩn loading
                    $btnText.removeClass('d-none');
                    $loading.addClass('d-none');
                    $submitBtn.prop('disabled', false);
                }
            });
        });
    });    
</script>
@endpush
@endsection
