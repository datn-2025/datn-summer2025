<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WeightCalculatorService
{    /**
     * Tính tổng cân nặng của giỏ hàng
     * Chỉ tính sách vật lý, ebook không tính cân nặng
     */
    public function calculateCartWeight($userId = null)
    {
        if (!$userId && !Auth::check()) {
            return config('ghn.default_weight', 200);
        }

        $userId = $userId ?? Auth::id();
        
        $cartItems = Cart::where('user_id', $userId)
            ->with(['book' => function($query) {
                $query->with('formats');
            }])
            ->get();        $totalWeight = 0;

        Log::info('Calculating cart weight', [
            'user_id' => $userId,
            'cart_items_count' => $cartItems->count()
        ]);

        foreach ($cartItems as $cartItem) {
            $book = $cartItem->book;
            if (!$book) continue;            // Kiểm tra xem có format vật lý không (paperback, hardcover)
            $hasPhysicalFormat = $book->formats()
                ->whereIn('format_name', ['Sách Vật Lý'])
                ->exists();

            Log::info('Book format check', [
                'book_id' => $book->id,
                'book_title' => $book->title,
                'quantity' => $cartItem->quantity,
                'has_physical_format' => $hasPhysicalFormat,
                'formats' => $book->formats->pluck('format_name')->toArray()
            ]);            // Chỉ tính cân nặng nếu có format vật lý
            if ($hasPhysicalFormat) {
                $bookWeight = 200; // 200g mỗi quyển sách vật lý (cố định)
                $itemWeight = $bookWeight * $cartItem->quantity;
                $totalWeight += $itemWeight;
                
                Log::info('Adding weight', [
                    'book_weight' => $bookWeight,
                    'quantity' => $cartItem->quantity,
                    'item_weight' => $itemWeight,
                    'total_weight' => $totalWeight
                ]);
            }
        }

        Log::info('Final cart weight', [
            'total_weight' => $totalWeight
        ]);

        // Nếu không có sách vật lý nào, trả về 0 (có thể sẽ không tínhphí ship)
        return $totalWeight > 0 ? $totalWeight : 0;
    }

    /**
     * Tính cân nặng cho đơn hàng cụ thể
     */
    public function calculateOrderWeight($orderItems)
    {
        $totalWeight = 0;

        foreach ($orderItems as $item) {
            $book = $item['book'] ?? Book::find($item['book_id'] ?? null);
            if (!$book) continue;            // Kiểm tra xem có format vật lý không
            $hasPhysicalFormat = $book->formats()
                ->whereIn('format_name', ['Sách Vật Lý'])
                ->exists();            if ($hasPhysicalFormat) {
                $bookWeight = 200; // 200g mỗi quyển sách vật lý (cố định)
                $quantity = $item['quantity'] ?? 1;
                $totalWeight += $bookWeight * $quantity;
            }
        }

        return $totalWeight;
    }

    /**
     * Kiểm tra xem đơn hàng có cần giao hàng không
     * (có ít nhất 1 sách vật lý)
     */
    public function needsShipping($items)
    {
        foreach ($items as $item) {
            $book = $item['book'] ?? Book::find($item['book_id'] ?? null);
            if (!$book) continue;            $hasPhysicalFormat = $book->formats()
                ->whereIn('format_name', ['Sách Vật Lý'])
                ->exists();

            if ($hasPhysicalFormat) {
                return true;
            }
        }

        return false;
    }
}
