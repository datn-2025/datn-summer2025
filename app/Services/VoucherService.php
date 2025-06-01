<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class VoucherService
{
    public function getAvailableVouchers(User $user)
    {
        $now = now();
        return Voucher::where('status', 'active')
            ->where('valid_from', '<=', $now)
            ->where('valid_to', '>=', $now)
            ->where(function($query) {
                $query->whereRaw('quantity > (SELECT COUNT(*) FROM applied_vouchers WHERE voucher_id = vouchers.id)')
                    ->orWhereNull('quantity');
            })
            ->whereNotExists(function($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('applied_vouchers')
                    ->whereRaw('applied_vouchers.voucher_id = vouchers.id')
                    ->where('user_id', $user->id);
            })
            ->get()
            ->map(function($voucher) {
                // Mặc định là chưa áp dụng
                $voucher->is_applied = false;
                return $voucher;
            });
    }

    public function calculateDiscount(Voucher $voucher, $subtotal = 0)
    {
        if (!$this->isVoucherValid($voucher, $subtotal)) {
            return 0;
        }

        $discount = $subtotal * ($voucher->discount_percent / 100);
        if ($voucher->max_discount && $discount > $voucher->max_discount) {
            $discount = $voucher->max_discount;
        }

        return $discount;
    }

    public function validateVoucher(string $code, User $user, $subtotal = 0)
    {
        $voucher = Voucher::where('code', $code)->first();

        if (!$voucher) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không tồn tại'
            ];
        }

        if (!$this->isVoucherValid($voucher, $subtotal)) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không còn hiệu lực'
            ];
        }

        // Kiểm tra số lần sử dụng của người dùng
        $userUsageCount = $voucher->appliedVouchers()
            ->where('user_id', $user->id)
            ->count();

        if ($userUsageCount >= 1) { // Giả sử mỗi người chỉ được dùng 1 lần
            return [
                'valid' => false,
                'message' => 'Bạn đã sử dụng mã giảm giá này'
            ];
        }

        return [
            'valid' => true,
            'voucher' => $voucher
        ];
    }

    protected function isVoucherValid(Voucher $voucher, $subtotal = 0)
    {
        $now = now();

        // Kiểm tra trạng thái và thời hạn
        if ($voucher->status !== 'active' ||
            $now->lt($voucher->valid_from) ||
            $now->gt($voucher->valid_to)) {
            return false;
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($subtotal < $voucher->min_order_value) {
            return false;
        }

        // Kiểm tra số lượng voucher còn lại
        $usedCount = $voucher->appliedVouchers()->count();
        if ($voucher->quantity && $usedCount >= $voucher->quantity) {
            return false;
        }

        return true;
    }
}
