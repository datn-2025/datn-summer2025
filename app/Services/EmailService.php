<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\OrderInvoice;

class EmailService
{
    public function sendOrderConfirmation(Order $order)
    {
        $order->load(['user', 'orderItems.book', 'address']);

        Mail::to($order->user->email)
            ->send(new OrderConfirmation($order));
    }

    public function sendOrderInvoice(Order $order)
    {
        if ($order->paymentStatus->name !== 'paid') {
            return;
        }

        $order->load(['user', 'orderItems.book', 'address', 'payments.paymentMethod']);

        Mail::to($order->user->email)
            ->send(new OrderInvoice($order));
    }
}
