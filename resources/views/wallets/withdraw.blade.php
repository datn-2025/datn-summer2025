@extends('layouts.account.layout')
@section('account_content')
<div class="bg-white border border-black shadow mb-8" style="border-radius: 0;">
    <div class="px-8 py-6 border-b border-black bg-black flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white uppercase tracking-wide mb-0">Rút Tiền Từ Ví</h1>
        <span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full font-bold text-lg">
            Số dư: ₫{{ number_format($wallet->balance ?? 0, 0, ',', '.') }}
        </span>
    </div>
    <div class="p-8">
        <form action="{{ route('wallet.withdraw') }}" method="POST" class="max-w-lg mx-auto">
            @csrf
            <div class="mb-4">
                <label for="amount" class="form-label font-semibold">Số tiền rút <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">₫</span>
                    <input type="number" min="1000" class="form-control" id="amount" name="amount" required placeholder="Nhập số tiền (VNĐ)" value="{{ old('amount') }}">
                </div>
                @error('amount')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label font-semibold">Tài khoản ngân hàng nhận tiền <span class="text-danger">*</span></label>
                <div class="border rounded p-3 bg-light mb-2">
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <input type="text" class="form-control" name="customer_name" placeholder="Tên chủ tài khoản" value="{{ old('customer_name', $userBankList['customer_name'] ?? '') }}" required>
                            @error('customer_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="text" class="form-control" name="bank_number" placeholder="Số tài khoản" value="{{ old('bank_number', $userBankList['bank_number'] ?? '') }}" required>
                            @error('bank_number')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 mt-2">
                            <input type="text" class="form-control" name="bank_name" placeholder="Tên ngân hàng" value="{{ old('bank_name', $userBankList['bank_name'] ?? '') }}" required>
                            @error('bank_name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Vui lòng kiểm tra kỹ thông tin trước khi xác nhận rút tiền.</small>
                </div>
            </div>
            <div class="mb-4">
                <label for="description" class="form-label font-semibold">Ghi chú (không bắt buộc)</label>
                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Ghi chú thêm nếu có...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-warning w-full py-2 uppercase font-bold">Rút tiền</button>
            <a href="{{ route('wallet.index') }}" class="btn btn-link d-block mt-3">Quay lại ví</a>
        </form>
    </div>
</div>
@endsection
