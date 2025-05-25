<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = DB::table('carts')
            ->join('books', 'carts.book_id', '=', 'books.id')
            ->leftJoin('book_formats', 'carts.book_format_id', '=', 'book_formats.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->where('carts.user_id', '4710b22a-37bb-11f0-a680-067090e2bd86')
            ->select(
                'carts.*',
                'books.title',
                'books.cover_image as image',
                'book_formats.format_name',
                'book_formats.price as current_price',
                'authors.name as author_name',
                'book_formats.stock'
            )
            ->get();

        $total = 0;
        foreach ($cart as $item) {
            $total += $item->price_at_addition * $item->quantity;
        }

        $discount = 0;
        $shipping = 30000;
        $finalTotal = $total - $discount + $shipping;

        return view('clients.cart.cart', compact('cart', 'total', 'discount', 'shipping', 'finalTotal'));

        /* Code chuẩn - Sẽ sử dụng sau khi fix xong bug
        $cart = [];
        $total = 0;
        $discount = 0;
        $shipping = 30000;
        
        if (Auth::check()) {
            $cart = DB::table('carts')
                ->join('books', 'carts.book_id', '=', 'books.id')
                ->leftJoin('book_formats', 'carts.book_format_id', '=', 'book_formats.id')
                ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
                ->where('carts.user_id', Auth::id())
                ->select(
                    'carts.*',
                    'books.title',
                    'books.image',
                    'book_formats.format_name',
                    'book_formats.price as current_price',
                    'authors.name as author_name'
                )
                ->get();
                
            foreach ($cart as $item) {
                $total += $item->price_at_addition * $item->quantity;
            }
        } else {
            $sessionCart = Session::get('cart', []);
            
            foreach ($sessionCart as $id => $details) {
                $book = DB::table('books')
                    ->leftJoin('book_formats', 'books.id', '=', 'book_formats.book_id')
                    ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
                    ->where('books.id', $id)
                    ->select(
                        'books.*',
                        'book_formats.format_name',
                        'book_formats.price as current_price',
                        'authors.name as author_name'
                    )
                    ->first();

                if ($book) {
                    $cart[] = (object)[
                        'id' => $id,
                        'title' => $book->title,
                        'image' => $book->image,
                        'quantity' => $details['quantity'],
                        'price_at_addition' => $details['price'],
                        'format_name' => $book->format_name,
                        'author_name' => $book->author_name
                    ];
                    $total += $details['price'] * $details['quantity'];
                }
            }
        }
        
        $appliedVoucher = Session::get('applied_voucher');
        if ($appliedVoucher) {
            $discount = $this->calculateDiscount($total, $appliedVoucher);
        }
        
        $finalTotal = $total - $discount + $shipping;
        
        return view('clients.cart.cart', compact('cart', 'total', 'discount', 'shipping', 'finalTotal'));
        */
    }

    public function addToCart(Request $request)
    {
        try {
            $bookId = $request->book_id;
            $quantity = $request->quantity ?? 1;
            $formatId = $request->format_id;

            $bookInfo = DB::table('books')
                ->join('book_formats', 'books.id', '=', 'book_formats.book_id')
                ->where('books.id', $bookId)
                ->where('book_formats.id', $formatId)
                ->select(
                    'books.title',
                    'book_formats.price',
                    'book_formats.stock',
                    'book_formats.id as format_id'
                )
                ->first();

            if (!$bookInfo) {
                return response()->json(['error' => 'Không tìm thấy sách'], 404);
            }

            if ($bookInfo->stock < $quantity) {
                return response()->json([
                    'error' => 'Số lượng yêu cầu vượt quá số lượng tồn kho. Tồn kho hiện tại: ' . $bookInfo->stock
                ], 400);
            }

            $existingCart = DB::table('carts')
                ->where('user_id', '4710b22a-37bb-11f0-a680-067090e2bd86')
                ->where('book_id', $bookId)
                ->where('book_format_id', $formatId)
                ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $quantity;
                if ($newQuantity > $bookInfo->stock) {
                    return response()->json([
                        'error' => 'Tổng số lượng vượt quá tồn kho. Tồn kho hiện tại: ' . $bookInfo->stock
                    ], 400);
                }

                DB::table('carts')
                    ->where('id', $existingCart->id)
                    ->update([
                        'quantity' => $newQuantity,
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('carts')->insert([
                    'user_id' => '4710b22a-37bb-11f0-a680-067090e2bd86',
                    'book_id' => $bookId,
                    'book_format_id' => $formatId,
                    'quantity' => $quantity,
                    'price_at_addition' => $bookInfo->price,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'success' => 'Thêm vào giỏ hàng thành công',
                'data' => [
                    'title' => $bookInfo->title,
                    'quantity' => $quantity,
                    'price' => $bookInfo->price
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    public function updateCart(Request $request)
    {
        try {
            $bookId = $request->book_id;
            $quantity = (int)$request->quantity;

            $stockInfo = DB::table('carts')
                ->join('book_formats', 'carts.book_format_id', '=', 'book_formats.id')
                ->where('carts.book_id', $bookId)
                ->where('carts.user_id', '4710b22a-37bb-11f0-a680-067090e2bd86')
                ->select('book_formats.stock', 'carts.id as cart_id')
                ->first();

            if (!$stockInfo) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }

            if ($quantity > $stockInfo->stock) {
                return response()->json([
                    'error' => 'Số lượng yêu cầu vượt quá số lượng tồn kho. Tồn kho hiện tại: ' . $stockInfo->stock
                ], 400);
            }

            if ($quantity > 0) {
                DB::table('carts')
                    ->where('id', $stockInfo->cart_id)
                    ->update([
                        'quantity' => $quantity,
                        'updated_at' => now()
                    ]);

                $updatedCart = DB::table('carts')
                    ->join('books', 'carts.book_id', '=', 'books.id')
                    ->join('book_formats', 'carts.book_format_id', '=', 'book_formats.id')
                    ->where('carts.id', $stockInfo->cart_id)
                    ->select(
                        'carts.quantity',
                        'book_formats.price',
                        'books.title'
                    )
                    ->first();

                return response()->json([
                    'success' => 'Cập nhật giỏ hàng thành công',
                    'data' => [
                        'quantity' => $updatedCart->quantity,
                        'price' => $updatedCart->price,
                        'total' => $updatedCart->quantity * $updatedCart->price
                    ]
                ]);
            } else {
                DB::table('carts')
                    ->where('id', $stockInfo->cart_id)
                    ->delete();

                return response()->json(['success' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            $bookId = $request->book_id;

            $cartItem = DB::table('carts')
                ->where('user_id', '4710b22a-37bb-11f0-a680-067090e2bd86')
                ->where('book_id', $bookId)
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
            }

            DB::table('carts')
                ->where('user_id', '4710b22a-37bb-11f0-a680-067090e2bd86')
                ->where('book_id', $bookId)
                ->delete();

            return response()->json(['success' => 'Xóa sản phẩm thành công']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    public function applyVoucher(Request $request)
    {
        // Fix cứng cho test - luôn trả về giảm giá 10%
        return response()->json([
            'success' => 'Áp dụng mã giảm giá thành công',
            'voucher' => [
                'discount_type' => 'percentage',
                'discount_value' => 10
            ]
        ]);
    }

    private function calculateDiscount($total, $voucher)
    {
        // Fix cứng cho test - luôn giảm 10%
        return $total * 0.1;
    }
}
