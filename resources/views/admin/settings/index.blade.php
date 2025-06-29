@extends('layouts.backend')
@section('title', 'Cấu hình website')
@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h5 class="mb-sm-0">Cài đặt website</h5>


                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Cấu hình website</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Thông Tin Cấu Hình</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4 border-left-primary">
                            <div class="card-header bg-gradient-primary text-dark fw-semibold">
                                <i class="fas fa-info-circle mr-1"></i> Thông Tin Cơ Bản
                            </div>
                            <div class="card-body">
                                <div class="form-group" >
                                    <label for="name_website"><i class="fas fa-globe mr-1 "></i> Tên Website</label>
                                    <input type="text" name="name_website" id="name_website" class="form-control @error('name_website') is-invalid @enderror" value="{{ old('name_website', $setting->name_website ?? '') }}">
                                    @error('name_website')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email"><i class="fas fa-envelope mr-1"></i> Email Liên Hệ</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $setting->email ?? '') }}">
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone"><i class="fas fa-phone-alt mr-1"></i> Số Điện Thoại</label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $setting->phone ?? '') }}">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address"><i class="fas fa-map-marker-alt mr-1"></i> Địa Chỉ</label>
                                    <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $setting->address ?? '') }}">
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 border-left-info shadow-sm">
                            <div class="card-header bg-gradient-info text-dark fw-semibold d-flex align-items-center">
                                <i class="fas fa-university mr-2"></i>
                                <span>Tài khoản ngân hàng nhận tiền</span>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info small mb-3" style="background: #e9f7fd; border-left: 4px solid #17a2b8;">
                                    <i class="fas fa-info-circle mr-1"></i> Vui lòng nhập chính xác thông tin tài khoản ngân hàng để nhận tiền từ hệ thống.
                                </div>
                                <div class="form-group mb-3">
                                    <label for="bank_name" class="fw-semibold"><i class="fas fa-university mr-1"></i> Tên ngân hàng</label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" placeholder="VD: Vietcombank, Techcombank..." value="{{ old('bank_name', isset($setting->bank_setting) ? json_decode($setting->bank_setting, true)['bank_name'] ?? '' : '') }}">
                                    <small class="form-text text-muted">Tên đầy đủ của ngân hàng (không viết tắt).</small>
                                    @error('bank_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="bank_number" class="fw-semibold"><i class="fas fa-credit-card mr-1"></i> Số tài khoản</label>
                                    <div class="input-group">
                                        <input type="text" name="bank_number" id="bank_number" class="form-control @error('bank_number') is-invalid @enderror" placeholder="Nhập số tài khoản ngân hàng" value="{{ old('bank_number', isset($setting->bank_setting) ? json_decode($setting->bank_setting, true)['bank_number'] ?? '' : '') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyBankNumber()" title="Copy"><i class="fas fa-copy"></i></button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Chỉ nhập số, không chứa dấu cách.</small>
                                    @error('bank_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="customer_name" class="fw-semibold"><i class="fas fa-user mr-1"></i> Tên chủ tài khoản</label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror" placeholder="VD: NGUYEN VAN A" value="{{ old('customer_name', isset($setting->bank_setting) ? json_decode($setting->bank_setting, true)['customer_name'] ?? '' : '') }}">
                                    <small class="form-text text-muted">Viết IN HOA, không dấu, đúng với tên trên tài khoản ngân hàng.</small>
                                    @error('customer_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4 border-left-success">
                            <div class="card-header bg-gradient-success text-dark fw-semibold">
                                <i class="fas fa-image mr-1"></i> Hình Ảnh & Logo
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="logo"><i class="fas fa-image mr-1"></i> Logo Website</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" id="logo" name="logo">
                                        <label class="custom-file-label" for="logo">Chọn file...</label>
                                    </div>
                                    @error('logo')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror

                                    @if(isset($setting) && $setting->logo)
                                        <div class="mt-3 text-center">
                                            <p class="text-muted small">Logo hiện tại:</p>
                                            <div class="image-preview p-2 border rounded mb-2">
                                                <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="img-fluid" style="max-height: 120px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group mt-4">
                                    <label for="favicon"><i class="fas fa-bookmark mr-1"></i> Favicon</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror" id="favicon" name="favicon">
                                        <label class="custom-file-label" for="favicon">Chọn file...</label>
                                    </div>
                                    @error('favicon')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror

                                    @if(isset($setting) && $setting->favicon)
                                        <div class="mt-3 text-center">
                                            <p class="text-muted small">Favicon hiện tại:</p>
                                            <div class="image-preview p-2 border rounded mb-2 d-inline-block">
                                                <img src="{{ asset('storage/' . $setting->favicon) }}" alt="Favicon" class="img-fluid" style="max-height: 64px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" style="background-color: #405189" class="btn btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-save white"></i>
                        </span>
                        <span class="text-white" >Lưu Cấu Hình</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyBankNumber() {
        const input = document.getElementById('bank_number');
        if (input) {
            input.select();
            input.setSelectionRange(0, 99999);
            document.execCommand('copy');
            toastr.success('Đã copy số tài khoản!');
        }
    }
    function copyPreviewBankNumber() {
        const el = document.getElementById('previewBankNumber');
        if (el) {
            const temp = document.createElement('input');
            temp.value = el.innerText;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            toastr.success('Đã copy số tài khoản!');
        }
    }
    $(document).ready(function () {
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);

            // Hiển thị preview ảnh
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const previewContainer = $(this).closest('.form-group').find('.image-preview');

                reader.onload = function(e) {
                    if (previewContainer.length === 0) {
                        const newPreview = `
                            <div class="mt-3 text-center">
                                <p class="text-muted small">Ảnh đã chọn:</p>
                                <div class="image-preview p-2 border rounded mb-2">
                                    <img src="${e.target.result}" class="img-fluid" style="max-height: 120px;">
                                </div>
                            </div>
                        `;
                        $(this).closest('.form-group').append(newPreview);
                    } else {
                        previewContainer.find('img').attr('src', e.target.result);
                    }
                };

                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush
