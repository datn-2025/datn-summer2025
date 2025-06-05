<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;

class PaymentObserver
{
    public function creating(Payment $payment)
    {
        // Nếu chưa có payment_status_id
        if (!$payment->payment_status_id) {
            // Lấy phương thức thanh toán
            $paymentMethod = PaymentMethod::find($payment->payment_method_id);
            
            if ($paymentMethod) {
                $statusName = '';
                
                // Thiết lập trạng thái mặc định dựa trên phương thức thanh toán
                if (str_contains(strtolower($paymentMethod->name), 'cod')) {
                    $statusName = 'Chưa thanh toán';
                } elseif (str_contains(strtolower($paymentMethod->name), 'vnpay') || 
                         str_contains(strtolower($paymentMethod->name), 'chuyển khoản')) {
                    $statusName = 'Chờ Xử Lý';
                }
                
                // Lấy ID của trạng thái tương ứng
                $status = PaymentStatus::where('name', $statusName)->first();
                if ($status) {
                    $payment->payment_status_id = $status->id;
                }
            }
        }
    }
}
