@extends('layouts.account.layout')
@section('account_content')
<div class="bg-white border border-black shadow mb-8" style="border-radius: 0;">
    <div class="px-8 py-6 border-b border-black bg-black flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white uppercase tracking-wide mb-0">Nạp Tiền Vào Ví</h1>
    </div>
    <div class="p-8">
        <form action="{{ route('wallet.deposit') }}" method="POST" class="max-w-lg mx-auto">
            @csrf
            <div class="mb-4">
                <label for="amount" class="form-label font-semibold">Số tiền nạp <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">₫</span>
                    <input type="number" min="1000" class="form-control" id="amount" name="amount" required placeholder="Nhập số tiền (VNĐ)">
                </div>
            </div>
            <div class="mb-4">
                <label for="payment_method" class="form-label font-semibold">Phương thức thanh toán <span class="text-danger">*</span></label>
                <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="">-- Chọn phương thức --</option>
                    <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                    <option value="vnpay">VNPay</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-full py-2 uppercase font-bold">Nạp tiền</button>
            <a href="{{ route('wallet.index') }}" class="btn btn-link d-block mt-3">Quay lại ví</a>
        </form>
    </div>
</div>
@endsection
