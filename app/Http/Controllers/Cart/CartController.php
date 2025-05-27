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
                    'books.cover_image as image',
                    'book_formats.format_name',
                    'book_formats.price as current_price',
                    'authors.name as author_name',
                    'book_formats.stock'
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
                    ->where('books.id', $details['book_id'])
                    ->where('book_formats.id', $details['format_id'])
                    ->select(
                        'books.id as book_id',
                        'books.title',
                        'books.cover_image as image',
                        'book_formats.format_name',
                        'book_formats.price as current_price',
                        'book_formats.stock',
                        'authors.name as author_name',
                        'book_formats.id as book_format_id'
                    )
                    ->first();

                if ($book) {
                    $cartItem = (object)[
                        'book_id' => $book->book_id,
                        'book_format_id' => $book->book_format_id,
                        'title' => $book->title,
                        'image' => $book->image,
                        'quantity' => $details['quantity'],
                        'price_at_addition' => $details['price'],
                        'format_name' => $book->format_name,
                        'author_name' => $book->author_name,
                        'stock' => $book->stock,
                        'current_price' => $book->current_price
                    ];
                    $cart[] = $cartItem;
                    $total += $details['price'] * $details['quantity'];
                }
            }
        }
        
        // Xử lý voucher nếu có
        $appliedVoucher = Session::get('applied_voucher');
        if ($appliedVoucher) {
            $discount = $this->calculateDiscount($total, $appliedVoucher);
        }
        
        $finalTotal = $total - $discount + $shipping;
        
        return view('clients.cart.cart', compact('cart', 'total', 'discount', 'shipping', 'finalTotal'));
    }

    public function addToCart(Request $request)
    {
        try {
            $bookId = $request->book_id;
            $quantity = $request->quantity ?? 1;
            $formatId = $request->format_id;

            // Kiểm tra thông tin sách và format
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

            if (Auth::check()) {
                // Xử lý cho user đã đăng nhập
                $existingCart = DB::table('carts')
                    ->where('user_id', Auth::id())
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
                        'user_id' => Auth::id(),
                        'book_id' => $bookId,
                        'book_format_id' => $formatId,
                        'quantity' => $quantity,
                        'price_at_addition' => $bookInfo->price,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else {
                // Xử lý cho guest user sử dụng session
                $cart = Session::get('cart', []);
                $cartKey = $bookId . '-' . $formatId;

                if (isset($cart[$cartKey])) {
                    $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
                    if ($newQuantity > $bookInfo->stock) {
                        return response()->json([
                            'error' => 'Tổng số lượng vượt quá tồn kho. Tồn kho hiện tại: ' . $bookInfo->stock
                        ], 400);
                    }
                    $cart[$cartKey]['quantity'] = $newQuantity;
                } else {
                    $cart[$cartKey] = [
                        'book_id' => $bookId,
                        'format_id' => $formatId,
                        'quantity' => $quantity,
                        'price' => $bookInfo->price
                    ];
                }

                Session::put('cart', $cart);
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
            $formatId = $request->format_id;

            if (Auth::check()) {
                // Xử lý cho user đã đăng nhập
                $stockInfo = DB::table('carts')
                    ->join('book_formats', 'carts.book_format_id', '=', 'book_formats.id')
                    ->where('carts.book_id', $bookId)
                    ->where('carts.user_id', Auth::id())
                    ->select('book_formats.stock', 'carts.id as cart_id', 'book_formats.price')
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

                    return response()->json([
                        'success' => 'Cập nhật giỏ hàng thành công',
                        'data' => [
                            'quantity' => $quantity,
                            'price' => $stockInfo->price,
                            'total' => $quantity * $stockInfo->price
                        ]
                    ]);
                } else {
                    DB::table('carts')
                        ->where('id', $stockInfo->cart_id)
                        ->delete();

                    return response()->json(['success' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
                }
            } else {
                // Xử lý cho guest user
                $cart = Session::get('cart', []);
                $cartKey = $bookId . '-' . $formatId;

                if (!isset($cart[$cartKey])) {
                    return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
                }

                // Kiểm tra tồn kho
                $stockInfo = DB::table('book_formats')
                    ->where('id', $formatId)
                    ->select('stock', 'price')
                    ->first();

                if ($quantity > $stockInfo->stock) {
                    return response()->json([
                        'error' => 'Số lượng yêu cầu vượt quá số lượng tồn kho. Tồn kho hiện tại: ' . $stockInfo->stock
                    ], 400);
                }

                if ($quantity > 0) {
                    $cart[$cartKey]['quantity'] = $quantity;
                    Session::put('cart', $cart);

                    return response()->json([
                        'success' => 'Cập nhật giỏ hàng thành công',
                        'data' => [
                            'quantity' => $quantity,
                            'price' => $stockInfo->price,
                            'total' => $quantity * $stockInfo->price
                        ]
                    ]);
                } else {
                    unset($cart[$cartKey]);
                    Session::put('cart', $cart);
                    return response()->json(['success' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            $bookId = $request->book_id;
            $formatId = $request->format_id;

            if (Auth::check()) {
                // Xử lý cho user đã đăng nhập
                $cartItem = DB::table('carts')
                    ->where('user_id', Auth::id())
                    ->where('book_id', $bookId)
                    ->where('book_format_id', $formatId)
                    ->first();

                if (!$cartItem) {
                    return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
                }

                DB::table('carts')
                    ->where('user_id', Auth::id())
                    ->where('book_id', $bookId)
                    ->where('book_format_id', $formatId)
                    ->delete();
            } else {
                // Xử lý cho guest user
                $cart = Session::get('cart', []);
                $cartKey = $bookId . '-' . $formatId;

                if (!isset($cart[$cartKey])) {
                    return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
                }

                unset($cart[$cartKey]);
                Session::put('cart', $cart);
            }

            return response()->json(['success' => 'Xóa sản phẩm thành công']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    public function applyVoucher(Request $request)
    {
        try {
            $code = $request->code;

            // Kiểm tra voucher có tồn tại và còn hiệu lực
            $voucher = DB::table('vouchers')
                ->where('code', $code)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('is_active', true)
                ->first();

            if (!$voucher) {
                return response()->json(['error' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'], 404);
            }

            // Lưu voucher vào session
            Session::put('applied_voucher', $voucher);

            return response()->json([
                'success' => 'Áp dụng mã giảm giá thành công',
                'voucher' => [
                    'discount_type' => $voucher->discount_type,
                    'discount_value' => $voucher->discount_value
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    private function calculateDiscount($total, $voucher)
    {
        if ($voucher->discount_type === 'percentage') {
            return $total * ($voucher->discount_value / 100);
        } else {
            return min($voucher->discount_value, $total);
        }
    }
}
