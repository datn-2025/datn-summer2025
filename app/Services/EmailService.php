<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\OrderInvoice;
use App\Mail\EbookPurchaseConfirmation;

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
        $order->load(['user', 'orderItems.book', 'address', 'payments.paymentMethod']);

        Mail::to($order->user->email)
            ->send(new OrderInvoice($order));
    }

    public function sendEbookPurchaseConfirmation(Order $order)
    {
        // Load relationships needed for the email
        $order->load(['user', 'orderItems.book.author', 'orderItems.bookFormat']);

        // Check if order contains any ebooks
        $hasEbooks = $order->orderItems->some(function ($item) {
            return $item->bookFormat && $item->bookFormat->format_name === 'Ebook';
        });
        // dd($hasEbooks);
        if (!$hasEbooks) {
            return;
        }

        Mail::to($order->user->email)
            ->send(new EbookPurchaseConfirmation($order));
    }
}
