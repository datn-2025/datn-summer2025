<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Book;
use Carbon\Carbon;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng của người dùng
     */
    public function index()
    {
        if (!Auth::check()) {
            Toastr::error('Bạn cần đăng nhập để xem giỏ hàng của bạn.', 'Lỗi');
            return redirect()->route('account.login');
        }

        $user = Auth::user();
        $cart = DB::table('carts')
            ->join('books', 'carts.book_id', '=', 'books.id')
            ->leftJoin('book_formats', 'carts.book_format_id', '=', 'book_formats.id')
            ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
            ->where('carts.user_id', $user->id)
            ->select(
                'carts.id',
                'carts.user_id',
                'carts.book_id',
                'carts.book_format_id',
                'carts.quantity',
                'carts.attribute_value_ids',
                'carts.price',
                'carts.created_at',
                'carts.updated_at',
                'books.title',
                'books.cover_image as image',
                'book_formats.format_name',
                'authors.name as author_name',
                'book_formats.stock',
                DB::raw('COALESCE(book_formats.format_name, "Bản thường") as format_name'),
                DB::raw('COALESCE(authors.name, "Chưa cập nhật") as author_name'),
                DB::raw('COALESCE(carts.quantity, 1) as quantity'),
                DB::raw('COALESCE(carts.price, 0) as price'),
                DB::raw('COALESCE(book_formats.stock, 0) as stock'),
                DB::raw('COALESCE(carts.attribute_value_ids, "[]") as attribute_value_ids')
            )
            ->get();

        $total = 0;
        foreach ($cart as $item) {
            $total += $item->price * $item->quantity;
        }

        // Lấy thông tin voucher đã áp dụng (nếu có)
        $appliedVoucher = session()->get('applied_voucher');
        $discount = $appliedVoucher ? $appliedVoucher['discount_amount'] : 0;

        return view('clients.cart.cart', compact('cart', 'total', 'discount'));
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.'], 401);
        }

        try {
            $bookId = $request->book_id;
            $quantity = $request->quantity ?? 1;
            $bookFormatId = $request->book_format_id;
            
            // Xử lý attribute_value_ids từ form
            $attributeValueIds = [];
            
            // Cách 1: Nếu gửi lên dưới dạng array
            if ($request->has('attribute_values') && is_array($request->attribute_values)) {
                $attributeValueIds = array_filter($request->attribute_values, function($value) {
                    return !empty($value);
                });
            }
            
            // Cách 2: Nếu gửi lên dưới dạng chuỗi JSON
            if ($request->has('attribute_value_ids') && !empty($request->attribute_value_ids)) {
                $decoded = json_decode($request->attribute_value_ids, true);
                if (is_array($decoded)) {
                    $attributeValueIds = $decoded;
                }
            }
            
            // Cách 3: Nếu gửi lên dưới dạng attributes[key] = value
            if ($request->has('attributes') && is_array($request->attributes)) {
                foreach ($request->attributes as $key => $value) {
                    if (!empty($value)) {
                        $attributeValueIds[] = $value;
                    }
                }
            }
            
            // Validation: Chỉ giữ lại những UUID hợp lệ và tồn tại trong database
            $validAttributeIds = [];
            if (!empty($attributeValueIds)) {
                $validAttributeIds = DB::table('attribute_values')
                    ->whereIn('id', $attributeValueIds)
                    ->pluck('id')
                    ->toArray();
                
                // Log để debug
                Log::info('Cart addToCart - Attribute validation:', [
                    'requested_ids' => $attributeValueIds,
                    'valid_ids' => $validAttributeIds
                ]);
            }
            
            // Loại bỏ duplicate và chuyển thành JSON string
            $validAttributeIds = array_unique($validAttributeIds);
            $attributeJson = json_encode(array_values($validAttributeIds));

            // Kiểm tra sách và lấy format có giá thấp nhất
            $bookInfo = DB::table('books')
                ->join('book_formats', 'books.id', '=', 'book_formats.book_id')
                ->where('books.id', $bookId)
                ->orderBy('book_formats.price', 'asc')
                ->select(
                    'books.id',
                    'books.title',
                    'book_formats.id as format_id',
                    'book_formats.price',
                    'book_formats.format_name',
                    'book_formats.stock'
                )
                ->first();

            // Tính giá cuối cùng bao gồm thuộc tính
            $finalPrice = $bookInfo->price;
            if (!empty($validAttributeIds)) {
                $attributeExtraCosts = DB::table('book_attribute_values')
                    ->where('book_id', $bookId)
                    ->whereIn('attribute_value_id', $validAttributeIds)
                    ->sum('extra_price');
                
                $finalPrice += $attributeExtraCosts;
                
                // Log để debug
                Log::info('Cart addToCart - Price calculation:', [
                    'base_price' => $bookInfo->price,
                    'attribute_extra_costs' => $attributeExtraCosts,
                    'final_price' => $finalPrice,
                    'attribute_ids' => $validAttributeIds
                ]);
            }

            if (!$bookInfo) {
                return response()->json(['error' => 'Không tìm thấy sách'], 404);
            }

            // Kiểm tra tồn kho
            if ($quantity > $bookInfo->stock) {
                return response()->json([
                    'error' => "Số lượng yêu cầu vượt quá số lượng tồn kho. Tồn kho hiện tại: {$bookInfo->stock}",
                    'available_stock' => $bookInfo->stock
                ], 422);
            }

            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa (bao gồm cả thuộc tính)
            $existingCart = DB::table('carts')
                ->where('user_id', Auth::id())
                ->where('book_id', $bookId)
                ->where('book_format_id', $bookFormatId ?? $bookInfo->format_id)
                ->where('attribute_value_ids', $attributeJson)
                ->first();

            if ($existingCart) {
                // Kiểm tra tổng số lượng sau khi thêm
                $newQuantity = $existingCart->quantity + $quantity;
                if ($newQuantity > $bookInfo->stock) {
                    return response()->json([
                        'error' => "Tổng số lượng vượt quá tồn kho. Tồn kho hiện tại: {$bookInfo->stock}",
                        'available_stock' => $bookInfo->stock,
                        'current_cart_quantity' => $existingCart->quantity
                    ], 422);
                }

                // Nếu đã có, tăng số lượng
                DB::table('carts')
                    ->where('id', $existingCart->id)
                    ->update([
                        'quantity' => $newQuantity,
                        'updated_at' => now()
                    ]);

                return response()->json([
                    'success' => 'Đã thêm ' . $quantity . ' sản phẩm "' . $bookInfo->title . '" vào giỏ hàng',
                    'stock' => $bookInfo->stock,
                    'current_quantity' => $newQuantity
                ]);
            } else {
                // Nếu chưa có, thêm mới
                DB::table('carts')->insert([
                    'id' => Str::uuid(),
                    'user_id' => Auth::id(),
                    'book_id' => $bookId,
                    'book_format_id' => $bookFormatId ?? $bookInfo->format_id,
                    'quantity' => $quantity,
                    'attribute_value_ids' => $attributeJson,
                    'price' => $finalPrice,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return response()->json([
                    'success' => 'Đã thêm sản phẩm "' . $bookInfo->title . '" vào giỏ hàng',
                    'stock' => $bookInfo->stock,
                    'current_quantity' => $quantity
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in addToCart:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Có lỗi xảy ra khi thêm vào giỏ hàng'], 500);
        }
    }

    public function updateCart(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Bạn cần đăng nhập để cập nhật giỏ hàng.'], 401);
            }

            $bookId = $request->book_id;
            $quantity = (int)$request->quantity;

            // Get the cart item first to get the book_format_id
            $cartItem = DB::table('carts')
                ->where('user_id', Auth::id())
                ->where('book_id', $bookId)
                ->first();

            if (!$cartItem) {
                return response()->json(['error' => 'Không tìm thấy sản phẩm trong giỏ hàng'], 404);
            }

            // Kiểm tra tồn kho và thông tin sách
            $bookInfo = DB::table('books')
                ->join('book_formats', function($join) use ($cartItem) {
                    $join->on('books.id', '=', 'book_formats.book_id')
                         ->where('book_formats.id', '=', $cartItem->book_format_id);
                })
                ->leftJoin('authors', 'books.author_id', '=', 'authors.id')
                ->where('books.id', $bookId)
                ->select(
                    'books.id',
                    'books.title',
                    'book_formats.id as format_id',
                    'book_formats.format_name',
                    'book_formats.stock',
                    'book_formats.price',
                    'authors.name as author_name',
                    DB::raw('COALESCE(book_formats.format_name, "Bản thường") as format_name'),
                    DB::raw('COALESCE(authors.name, "Chưa cập nhật") as author_name'),
                    DB::raw('COALESCE(book_formats.stock, 0) as stock'),
                    DB::raw('COALESCE(book_formats.price, 0) as price')
                )
                ->first();

            if (!$bookInfo) {
                return response()->json(['error' => 'Không tìm thấy sách hoặc định dạng sách'], 404);
            }

            if ($quantity > $bookInfo->stock) {
                return response()->json([
                    'error' => "Số lượng yêu cầu vượt quá số lượng tồn kho. Tồn kho hiện tại: {$bookInfo->stock}",
                    'available_stock' => $bookInfo->stock
                ], 422);
            }

            if ($quantity > 0) {
                DB::table('carts')
                    ->where('user_id', Auth::id())
                    ->where('book_id', $bookId)
                    ->update([
                        'quantity' => $quantity,
                        'updated_at' => now()
                    ]);

                return response()->json([
                    'success' => 'Đã cập nhật số lượng sản phẩm',
                    'data' => [
                        'id' => $cartItem->id,
                        'book_id' => $bookId,
                        'quantity' => $quantity,
                        'price' => $bookInfo->price,
                        'total' => $bookInfo->price * $quantity,
                        'book_title' => $bookInfo->title,
                        'author_name' => $bookInfo->author_name,
                        'format_name' => $bookInfo->format_name,
                        'stock' => $bookInfo->stock
                    ]
                ]);
            } else {
                DB::table('carts')
                    ->where('user_id', Auth::id())
                    ->where('book_id', $bookId)
                    ->delete();

                return response()->json(['success' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
            }
        } catch (\Exception $e) {
            Log::error('Error in updateCart:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Có lỗi xảy ra khi cập nhật giỏ hàng: ' . $e->getMessage()], 500);
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Bạn cần đăng nhập để xóa sản phẩm khỏi giỏ hàng.'], 401);
            }

            $bookId = $request->book_id;

            DB::table('carts')
                ->where('user_id', Auth::id())
                ->where('book_id', $bookId)
                ->delete();

            return response()->json(['success' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi xóa sản phẩm'], 500);
        }
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric|min:0'
        ]);

        $voucherCode = strtoupper($request->code);
        
        // Kiểm tra voucher có tồn tại không
        $basicVoucher = DB::table('vouchers')
            ->where('code', $voucherCode)
            ->first();
            
        if (!$basicVoucher) {
            Log::info("Voucher không tồn tại trong database: " . $voucherCode);
            return response()->json([
                'error' => 'Mã giảm giá không tồn tại'
            ], 404);
        }

        // Debug: Log chi tiết thông tin voucher
        Log::info("Chi tiết voucher:", [
            'code' => $basicVoucher->code,
            'status' => $basicVoucher->status,
            'valid_from' => $basicVoucher->valid_from,
            'valid_to' => $basicVoucher->valid_to,
            'quantity' => $basicVoucher->quantity,
            'deleted_at' => $basicVoucher->deleted_at
        ]);

        // 1. Kiểm tra voucher đã bị xóa mềm (deleted_at)
        if ($basicVoucher->deleted_at !== null) {
            Log::info("Voucher đã bị xóa: " . $voucherCode);
            return response()->json([
                'error' => 'Mã giảm giá không tồn tại'
            ], 404);
        }

        // 2. Kiểm tra trạng thái voucher
        switch (strtolower($basicVoucher->status)) {
            case 'inactive':
            case 'locked':
            case 'disabled':
                Log::info("Voucher bị khóa hoặc vô hiệu hóa: " . $voucherCode, [
                    'status' => $basicVoucher->status
                ]);
                return response()->json([
                    'error' => 'Mã giảm giá đã bị vô hiệu hóa hoặc bị khóa'
                ], 400);
                
            case 'expired':
                Log::info("Voucher đã hết hạn theo status: " . $voucherCode);
                return response()->json([
                    'error' => 'Mã giảm giá đã hết hạn'
                ], 400);
                
            case 'used':
            case 'exhausted':
                Log::info("Voucher đã được sử dụng hết: " . $voucherCode);
                return response()->json([
                    'error' => 'Mã giảm giá đã hết lượt sử dụng'
                ], 400);
                
            case 'pending':
            case 'scheduled':
                Log::info("Voucher chưa được kích hoạt: " . $voucherCode);
                return response()->json([
                    'error' => 'Mã giảm giá chưa được kích hoạt'
                ], 400);
                
            case 'active':
                // Trạng thái hợp lệ, tiếp tục kiểm tra
                break;
                
            default:
                Log::info("Voucher có trạng thái không xác định: " . $voucherCode, [
                    'status' => $basicVoucher->status
                ]);
                return response()->json([
                    'error' => 'Mã giảm giá không hợp lệ'
                ], 400);
        }

        // 3. Kiểm tra thời gian hiệu lực
        $now = Carbon::now();
        
        if ($basicVoucher->valid_from !== null || $basicVoucher->valid_to !== null) {
            $validFrom = $basicVoucher->valid_from ? Carbon::parse($basicVoucher->valid_from) : null;
            $validTo = $basicVoucher->valid_to ? Carbon::parse($basicVoucher->valid_to) : null;
            
            // Kiểm tra chưa tới thời gian hiệu lực
            if ($validFrom && $now < $validFrom) {
                Log::info("Voucher chưa tới thời gian hiệu lực: " . $voucherCode, [
                    'now' => $now->format('Y-m-d H:i:s'),
                    'valid_from' => $validFrom->format('Y-m-d H:i:s')
                ]);
                return response()->json([
                    'error' => sprintf('Mã giảm giá này sẽ có hiệu lực từ %s', 
                        $validFrom->format('d/m/Y H:i'))
                ], 400);
            }
            
            // Kiểm tra đã hết hạn
            if ($validTo && $now > $validTo) {
                Log::info("Voucher đã hết hạn: " . $voucherCode, [
                    'now' => $now->format('Y-m-d H:i:s'),
                    'valid_to' => $validTo->format('Y-m-d H:i:s')
                ]);
                return response()->json([
                    'error' => sprintf('Mã giảm giá đã hết hạn vào %s', 
                        $validTo->format('d/m/Y H:i'))
                ], 400);
            }
        }

        // 4. Kiểm tra số lượng còn lại
        if ($basicVoucher->quantity !== null && $basicVoucher->quantity <= 0) {
            Log::info("Voucher hết số lượng: " . $voucherCode, [
                'quantity' => $basicVoucher->quantity
            ]);
            return response()->json([
                'error' => 'Mã giảm giá đã hết lượt sử dụng'
            ], 400);
        }

        // 5. Kiểm tra giá trị đơn hàng tối thiểu
        if ($basicVoucher->min_order_value && $request->total < $basicVoucher->min_order_value) {
            Log::info("Đơn hàng không đạt giá trị tối thiểu: " . $voucherCode, [
                'order_total' => $request->total,
                'min_order_value' => $basicVoucher->min_order_value
            ]);
            return response()->json([
                'error' => sprintf('Giá trị đơn hàng tối thiểu phải từ %s để sử dụng mã giảm giá này', 
                    number_format($basicVoucher->min_order_value) . 'đ')
            ], 400);
        }

        // 6. Kiểm tra xem user đã sử dụng voucher này chưa (nếu cần)
        if (Auth::check()) {
            $userUsedVoucher = session('applied_voucher');
            if ($userUsedVoucher && $userUsedVoucher['code'] === $basicVoucher->code) {
                return response()->json([
                    'error' => 'Bạn đã áp dụng mã giảm giá này rồi'
                ], 400);
            }
        }

        // 7. Tính toán số tiền giảm
        $discountAmount = ($request->total * $basicVoucher->discount_percent) / 100;
        if ($basicVoucher->max_discount && $discountAmount > $basicVoucher->max_discount) {
            $discountAmount = $basicVoucher->max_discount;
        }

        // Đảm bảo số tiền giảm không vượt quá tổng đơn hàng
        if ($discountAmount > $request->total) {
            $discountAmount = $request->total;
        }

        // 8. Áp dụng voucher thành công - cập nhật số lượng và lưu session
        try {
            DB::beginTransaction();
            
            // Giảm số lượng voucher nếu có giới hạn số lượng
            if ($basicVoucher->quantity !== null) {
                $updated = DB::table('vouchers')
                    ->where('code', $voucherCode)
                    ->where('quantity', '>', 0) // Đảm bảo vẫn còn số lượng
                    ->decrement('quantity');
                    
                if (!$updated) {
                    DB::rollBack();
                    return response()->json([
                        'error' => 'Mã giảm giá đã hết lượt sử dụng'
                    ], 400);
                }
            }

            // Lưu voucher vào session
            session(['applied_voucher' => [
                'code' => $basicVoucher->code,
                'discount_amount' => $discountAmount,
                'applied_at' => now()->toDateTimeString()
            ]]);
            
            DB::commit();
            
            Log::info("Áp dụng voucher thành công: " . $voucherCode, [
                'discount_amount' => $discountAmount,
                'order_total' => $request->total
            ]);

            return response()->json([
                'success' => 'Áp dụng mã giảm giá thành công',
                'discount' => $discountAmount,
                'voucher' => [
                    'code' => $basicVoucher->code,
                    'description' => $basicVoucher->description,
                    'discount_percent' => $basicVoucher->discount_percent,
                    'max_discount' => $basicVoucher->max_discount,
                    'min_order_value' => $basicVoucher->min_order_value
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi áp dụng voucher: ' . $voucherCode, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Có lỗi xảy ra khi áp dụng mã giảm giá'
            ], 500);
        }
    }

    public function removeVoucher()
    {
        session()->forget('applied_voucher');
        
        return response()->json([
            'success' => 'Đã xóa mã giảm giá'
        ]);
    }

    /**
     * Xóa tất cả sản phẩm trong giỏ hàng
     */
    public function clearCart(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Bạn cần đăng nhập để thực hiện chức năng này.'], 401);
            }

            $deletedCount = DB::table('carts')
                ->where('user_id', Auth::id())
                ->delete();

            // Xóa voucher đã áp dụng
            session()->forget('applied_voucher');

            return response()->json([
                'success' => "Đã xóa tất cả sản phẩm khỏi giỏ hàng",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error in clearCart:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Có lỗi xảy ra khi xóa giỏ hàng'], 500);
        }
    }

    /**
     * Thêm tất cả sản phẩm từ wishlist vào giỏ hàng
     */
    public function addAllWishlistToCart(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Bạn cần đăng nhập để thực hiện chức năng này.'], 401);
            }

            $userId = Auth::id();
            
            // Lấy tất cả sản phẩm trong wishlist
            $wishlistItems = DB::table('wishlists')
                ->join('books', 'wishlists.book_id', '=', 'books.id')
                ->join('book_formats', function($join) {
                    $join->on('books.id', '=', 'book_formats.book_id');
                })
                ->where('wishlists.user_id', $userId)
                ->select(
                    'books.id as book_id',
                    'books.title',
                    'book_formats.id as format_id',
                    'book_formats.price',
                    'book_formats.stock'
                )
                ->get();

            if ($wishlistItems->isEmpty()) {
                return response()->json([
                    'error' => 'Danh sách yêu thích của bạn trống'
                ], 404);
            }

            $addedCount = 0;
            $skippedItems = [];

            foreach ($wishlistItems as $item) {
                // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa (không có thuộc tính đặc biệt)
                $existingCart = DB::table('carts')
                    ->where('user_id', $userId)
                    ->where('book_id', $item->book_id)
                    ->where('book_format_id', $item->format_id)
                    ->where('attribute_value_ids', '[]')
                    ->first();

                if ($existingCart) {
                    // Nếu đã có, tăng số lượng (nếu đủ tồn kho)
                    $newQuantity = $existingCart->quantity + 1;
                    if ($newQuantity <= $item->stock) {
                        DB::table('carts')
                            ->where('id', $existingCart->id)
                            ->update([
                                'quantity' => $newQuantity,
                                'updated_at' => now()
                            ]);
                        $addedCount++;
                    } else {
                        $skippedItems[] = [
                            'title' => $item->title,
                            'reason' => 'Không đủ tồn kho'
                        ];
                    }
                } else {
                    // Nếu chưa có, thêm mới (nếu có tồn kho)
                    if ($item->stock > 0) {
                        DB::table('carts')->insert([
                            'id' => Str::uuid(),
                            'user_id' => $userId,
                            'book_id' => $item->book_id,
                            'book_format_id' => $item->format_id,
                            'quantity' => 1,
                            'attribute_value_ids' => '[]',
                            'price' => $item->price,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $addedCount++;
                    } else {
                        $skippedItems[] = [
                            'title' => $item->title,
                            'reason' => 'Hết hàng'
                        ];
                    }
                }
            }

            $response = [
                'success' => "Đã thêm {$addedCount} sản phẩm từ danh sách yêu thích vào giỏ hàng",
                'added_count' => $addedCount
            ];

            if (!empty($skippedItems)) {
                $response['skipped_items'] = $skippedItems;
                $response['message'] = "Một số sản phẩm không thể thêm vào giỏ hàng";
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error in addAllWishlistToCart:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Có lỗi xảy ra khi thêm từ danh sách yêu thích'], 500);
        }
    }
}
