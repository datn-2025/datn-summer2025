@extends('layouts.account.layout')
@section('account_content')
<div class="bg-white border border-black shadow mb-8" style="border-radius: 0;">
    <div class="px-8 py-6 border-b border-black bg-black flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white uppercase tracking-wide mb-0">Ví Của Tôi</h1>
        <span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full font-bold text-lg">
            Số dư: ₫{{ number_format($wallet->balance ?? 0, 0, ',', '.') }}
        </span>
    </div>
    <div class="p-8">
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <a href="{{ route('wallet.deposit.form') }}" class="btn btn-primary flex-1 text-center py-3 uppercase font-semibold shadow-sm">
                <i class="fas fa-plus-circle me-2"></i> Nạp tiền
            </a>
            <a href="{{ route('wallet.withdraw.form') }}" class="btn btn-warning flex-1 text-center py-3 uppercase font-semibold shadow-sm">
                <i class="fas fa-arrow-circle-up me-2"></i> Rút tiền
            </a>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 font-bold">Lịch sử giao dịch</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Loại</th>
                                <th class="text-end">Số tiền</th>
                                <th class="text-center">Phương thức</th>
                                <th class="text-center">Ngày</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $i => $transaction)
                                <tr>
                                    <td class="text-center">{{ $transactions->firstItem() + $i }}</td>
                                    <td class="text-center">
                                        @if($transaction->type === 'Nap')
                                            <span class="badge bg-primary">Nạp</span>
                                        @elseif($transaction->type === 'Rut')
                                            <span class="badge bg-warning text-dark">Rút</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($transaction->type) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold {{ $transaction->type === 'deposit' ? 'text-success' : 'text-danger' }}">
                                        ₫{{ number_format($transaction->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($transaction->payment_method === 'bank_transfer')
                                            <span >Chuyển khoản ngân hàng</span>
                                        @else
                                            <span >VNPay</span>
                                        @endif
                                    <td class="text-center">{{ $transaction->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @if($transaction->status === 'pending' || $transaction->status === 'Chờ Xử Lý')
                                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                        @elseif($transaction->status === 'success' || $transaction->status === 'Thành Công')
                                            <span class="badge bg-success">Thành công</span>
                                        @elseif($transaction->status === 'rejected' || $transaction->status === 'Từ Chối')
                                            <span class="badge bg-danger">Từ chối</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $transaction->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Chưa có giao dịch nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- <div class="p-3">
                    {{ $transactions->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
