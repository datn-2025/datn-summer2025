@extends('layouts.backend')

@section('title', 'Chỉnh sửa Voucher')
@section('breadcrumb-parent', 'Voucher')
@section('breadcrumb-child', 'Chỉnh sửa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chỉnh sửa Voucher</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Mã Voucher <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                           id="code" name="code" value="{{ old('code', $voucher->code) }}" required>
                                    @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <input type="text" class="form-control @error('description') is-invalid @enderror"
                                           id="description" name="description" value="{{ old('description', $voucher->description) }}">
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
                                           id="discount_percent" name="discount_percent"
                                           value="{{ old('discount_percent', $voucher->discount_percent) }}"
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
                                           id="max_discount" name="max_discount"
                                           value="{{ old('max_discount', $voucher->max_discount) }}" min="0" step="0.01" required>
                                    @error('max_discount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="min_order_value">Đơn tối thiểu (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('min_order_value') is-invalid @enderror"
                                           id="min_order_value" name="min_order_value"
                                           value="{{ old('min_order_value', $voucher->min_order_value) }}" min="0" step="0.01" required>
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
                                           id="quantity" name="quantity"
                                           value="{{ old('quantity', $voucher->quantity) }}" min="1" required>
                                    @error('quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="valid_from">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('valid_from') is-invalid @enderror"
                                           id="valid_from" name="valid_from"
                                           value="{{ old('valid_from', $voucher->valid_from->format('Y-m-d')) }}" required>
                                    @error('valid_from')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="valid_to">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('valid_to') is-invalid @enderror"
                                           id="valid_to" name="valid_to"
                                           value="{{ old('valid_to', $voucher->valid_to->format('Y-m-d')) }}" required>
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
                                        <option value="active" {{ old('status', $voucher->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('status', $voucher->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
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
                                        <option value="all" {{ old('condition_type', $voucher->conditions->first()->type ?? '') == 'all' ? 'selected' : '' }}>Tất cả sản phẩm</option>
                                        <option value="category" {{ old('condition_type', $voucher->conditions->first()->type ?? '') == 'category' ? 'selected' : '' }}>Theo danh mục</option>
                                        <option value="author" {{ old('condition_type', $voucher->conditions->first()->type ?? '') == 'author' ? 'selected' : '' }}>Theo tác giả</option>
                                        <option value="brand" {{ old('condition_type', $voucher->conditions->first()->type ?? '') == 'brand' ? 'selected' : '' }}>Theo thương hiệu</option>
                                        <option value="book" {{ old('condition_type', $voucher->conditions->first()->type ?? '') == 'book' ? 'selected' : '' }}>Theo sách</option>
                                    </select>
                                    @error('condition_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- Select2 dropdown for specific conditions --}}
                                <div class="form-group" id="condition_ids_group" style="{{ old('condition_type', $voucher->conditions->first()->type ?? '') && old('condition_type', $voucher->conditions->first()->type ?? '') !== 'all' ? '' : 'display: none;' }}">
                                    <label for="condition_ids">Chọn đối tượng áp dụng <span class="text-danger">*</span></label>
                                     <select class="form-control @error('condition_ids') is-invalid @enderror"
                                            id="condition_ids" name="condition_ids[]" multiple="multiple" style="width: 100%;">
                                        {{-- Existing selected options will be pre-populated by the script --}}
                                        {{-- You might need to add options here if old() or $voucher->conditions exist on load --}}
                                    </select>
                                    @error('condition_ids')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var $conditionTypeSelect = $('#condition_type');
        var $conditionIdsGroup = $('#condition_ids_group');
        var $conditionIdsSelect = $('#condition_ids');

        // Initialize Select2
        $conditionIdsSelect.select2({
            placeholder: "-- Chọn đối tượng áp dụng --",
            allowClear: true,
            ajax: {
                url: '{{ route('admin.vouchers.conditions') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term, // search term
                        type: $conditionTypeSelect.val()
                    };
                    return query;
                },
                processResults: function (data) {
                    // data format is expected to be [{id: ..., text: ...}, ...]
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        // Function to toggle visibility of the Select2 dropdown
        function toggleConditionIdsVisibility() {
            var selectedType = $conditionTypeSelect.val();
            if (selectedType && selectedType !== 'all') {
                $conditionIdsGroup.show();
                // No need to clear on type change if we handle initial load
            } else {
                $conditionIdsGroup.hide();
                 // Clear current selections and data when hidden
                $conditionIdsSelect.val(null).trigger('change');
            }
        }

        // Handle change event on condition type
        $conditionTypeSelect.on('change', function() {
            toggleConditionIdsVisibility();
        });

        // Trigger visibility check on page load
        toggleConditionIdsVisibility();

        // --- Load existing conditions for the edit page ---
        var existingConditions = @json(old('condition_ids', $voucher->conditions->pluck('condition_id')->toArray()));
        var selectedTypeOnLoad = $conditionTypeSelect.val();

        if (existingConditions.length > 0 && selectedTypeOnLoad && selectedTypeOnLoad !== 'all') {
             // We need to fetch the full data for the existing conditions
             // This requires another AJAX call or passing the full objects from backend

             // Simpler approach: if backend passed full condition objects to the view
             // For example, if $voucher->conditions loaded with related models (book, category, etc.)
             var preselectedOptions = [];
             @if($voucher->conditions->isNotEmpty())
                @foreach($voucher->conditions as $condition)
                    @php
                        $text = '';
                        switch($condition->type) {
                            case 'category': $text = 'Danh mục: ' . ($condition->categoryCondition->name ?? 'Không xác định'); break;
                            case 'author': $text = 'Tác giả: ' . ($condition->authorCondition->name ?? 'Không xác định'); break;
                            case 'brand': $text = 'NXB: ' . ($condition->brandCondition->name ?? 'Không xác định'); break;
                            case 'book': $text = 'Sách: ' . ($condition->bookCondition->title ?? 'Không xác định') . ' - ' . ($condition->bookCondition->author->name ?? ''); break;
                        }
                    @endphp
                    preselectedOptions.push({
                         id: '{{ $condition->condition_id }}',
                         text: '{{ $text }}'
                    });
                @endforeach
             @endif

             if(preselectedOptions.length > 0){
                 // Use the Select2 add option method to add pre-selected items
                 preselectedOptions.forEach(function(option) {
                      // Check if option already exists (might happen with old() values)
                      if ($conditionIdsSelect.find('option[value="' + option.id + '"]').length === 0) {
                          var newOption = new Option(option.text, option.id, true, true);
                          $conditionIdsSelect.append(newOption).trigger('change');
                      }
                 });
             }
        }
        // --- End Load existing conditions ---

    });
</script>
@endpush
