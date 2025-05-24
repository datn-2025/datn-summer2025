@extends('layouts.backend')

@section('title', 'Thêm Voucher Mới')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Thêm Voucher Mới</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Vouchers</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.vouchers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã voucher <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}"
                                       placeholder="Nhập mã voucher">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="discount_percent" class="form-label">
                                    Phần trăm giảm giá (%) <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.1" min="0" max="100"
                                       class="form-control @error('discount_percent') is-invalid @enderror" 
                                       id="discount_percent" name="discount_percent"
                                       value="{{ old('discount_percent') }}"
                                       placeholder="Nhập phần trăm giảm giá">
                                @error('discount_percent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="max_discount" class="form-label">
                                    Giảm giá tối đa (VNĐ) <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('max_discount') is-invalid @enderror" 
                                       id="max_discount" name="max_discount"
                                       value="{{ old('max_discount') }}"
                                       placeholder="Nhập số tiền giảm tối đa">
                                @error('max_discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_order_value" class="form-label">
                                    Giá trị đơn hàng tối thiểu (VNĐ) <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('min_order_value') is-invalid @enderror" 
                                       id="min_order_value" name="min_order_value"
                                       value="{{ old('min_order_value') }}"
                                       placeholder="Nhập giá trị đơn hàng tối thiểu">
                                @error('min_order_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">
                                    Số lượng <span class="text-danger">*</span>
                                </label>
                                <input type="number" min="1"
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity"
                                       value="{{ old('quantity') }}"
                                       placeholder="Nhập số lượng voucher">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="valid_from" class="form-label">
                                            Ngày bắt đầu <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('valid_from') is-invalid @enderror" 
                                               id="valid_from" name="valid_from"
                                               value="{{ old('valid_from') }}">
                                        @error('valid_from')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="valid_to" class="form-label">
                                            Ngày kết thúc <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control @error('valid_to') is-invalid @enderror" 
                                               id="valid_to" name="valid_to"
                                               value="{{ old('valid_to') }}">
                                        @error('valid_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3"
                                          placeholder="Nhập mô tả cho voucher">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label d-block">Trạng thái</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_active" name="status" value="active" 
                                           class="form-check-input" 
                                           {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_active">Kích hoạt</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="status_inactive" name="status" value="inactive"
                                           class="form-check-input"
                                           {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_inactive">Không kích hoạt</label>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-light me-2">Hủy</a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-save-line align-bottom me-1"></i> Tạo voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/cleave.js/cleave.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Cleave.js for number formatting
        new Cleave('#max_discount', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        
        new Cleave('#min_order_value', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    });
</script>
@endpush