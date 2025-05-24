<?php

namespace App\Http\Controllers\Wishlists;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class WishlistController extends Controller
{
  public function add(Request $request)
  {
    try {
      $userId = "4710b22a-37bb-11f0-a680-067090e2bd86"; // giả định tạm thời

      $bookId = $request->input('book_id');
      if (!$bookId) {
        return response()->json([
          'success' => false,
          'message' => 'Thiếu ID sản phẩm'
        ]);
      }

      // Kiểm tra xem đã có sản phẩm trong wishlist chưa
      $exists = DB::table('wishlists')
        ->where('user_id', $userId)
        ->where('book_id', $bookId)
        ->exists();

      if ($exists) {
        return response()->json([
          'success' => false,
          'message' => 'Sản phẩm đã có trong danh sách yêu thích'
        ]);
      }

      // Sinh UUID cho trường id
      $uuid = (string) Str::uuid();

      DB::table('wishlists')->insert([
        'id' => $uuid,
        'user_id' => $userId,
        'book_id' => $bookId,
        'created_at' => now(),
        'updated_at' => now(),
      ]);

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      Log::error('Lỗi khi thêm wishlist: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Lỗi server: ' . $e->getMessage(),
      ], 500);
    }
  }

  public function getWishlist(Request $request)
  {
    try {
      $userId = "4710b22a-37bb-11f0-a680-067090e2bd86"; // giả định tạm thời

      $wishlist = DB::table('wishlists')
        ->join('books', 'wishlists.book_id', '=', 'books.id')
        ->join('authors', 'books.author_id', '=', 'authors.id')
        ->leftJoin('brands', 'books.brand_id', '=', 'brands.id')
        ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
        ->where('wishlists.user_id', $userId)
        ->select(
          'books.id as book_id',
          'books.cover_image',
          'books.title',
          'authors.name as author_name',
          'brands.name as brand_name',
          'categories.name as category_name',
          'wishlists.created_at'
        )
        ->orderBy('wishlists.created_at', 'desc')
        ->paginate(10);

      // Lấy thống kê cơ bản
      $statistics = [
        'total' => $wishlist->total(),
        'recently_added' => $wishlist->take(5)
      ];

      return view('Wishlists.Wishlist', [
        'wishlist' => $wishlist,
        'statistics' => $statistics
      ]);
    } catch (\Exception $e) {
      Log::error('Lỗi khi lấy danh sách yêu thích: ' . $e->getMessage());
      return view('Wishlists.Wishlist', [
        'wishlist' => collect(),
        'statistics' => [
          'total' => 0,
          'in_stock' => 0,
          'out_of_stock' => 0,
          'total_value' => 0
        ]
      ]);
    }
  }


  public function delete(Request $request)
  {
    try {
      $userId = "4710b22a-37bb-11f0-a680-067090e2bd86"; // giả định tạm thời

      $bookId = $request->input('book_id');
      if (!$bookId) {
        return response()->json([
          'success' => false,
          'message' => 'Thiếu ID sản phẩm'
        ]);
      }

      $deleted = DB::table('wishlists')
        ->where('user_id', $userId)
        ->where('book_id', $bookId)
        ->delete();

      if ($deleted) {
        return response()->json(['success' => true]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Không tìm thấy sản phẩm trong danh sách yêu thích'
        ]);
      }
    } catch (\Exception $e) {
      Log::error('Lỗi khi xóa sản phẩm khỏi wishlist: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Lỗi server: ' . $e->getMessage(),
      ], 500);
    }
  }

  public function deleteAll(Request $request)
  {
    try {
      $userId = "4710b22a-37bb-11f0-a680-067090e2bd86"; // giả định tạm thời

      $deleted = DB::table('wishlists')->where('user_id', $userId)->delete();

      Log::info("Xóa tất cả wishlist user $userId, số bản ghi bị xóa: $deleted");
      if ($deleted === 0) {
        return response()->json([
          'success' => false,
          'message' => 'Không có sản phẩm nào trong danh sách yêu thích'
        ]);
      }
      // Xóa tất cả wishlist của người dùng

      return response()->json([
        'success' => $deleted > 0,
        'message' => $deleted > 0 ? 'Đã xóa tất cả' : 'Không có sách nào để xóa'
      ]);
    } catch (\Exception $e) {
      Log::error('Lỗi khi xóa tất cả wishlist: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Lỗi server: ' . $e->getMessage(),
      ], 500);
    }
  }
  public function addToCartFromWishlist(Request $request)
  {
    $userId = "4710b22a-37bb-11f0-a680-067090e2bd86"; // userId cứng fix sẵn

    $bookId = $request->input('book_id');
    $bookFormatId = $request->input('book_format_id'); // có thể null
    $attributes = $request->input('attributes'); // nhận attributes dạng mảng hoặc null

    if (!$bookId) {
      return response()->json(['success' => false, 'message' => 'Thiếu book_id']);
    }

    // Kiểm tra sản phẩm có trong wishlist không
    $existsInWishlist = DB::table('wishlists')
      ->where('user_id', $userId)
      ->where('book_id', $bookId)
      ->exists();

    if (!$existsInWishlist) {
      return response()->json(['success' => false, 'message' => 'Sản phẩm không có trong danh sách yêu thích']);
    }

    // Tạo query kiểm tra trùng trong carts
    $query = DB::table('carts')
      ->where('user_id', $userId)
      ->where('book_id', $bookId)
      ->where('book_format_id', $bookFormatId);

    if ($attributes) {
      // So sánh JSON string hóa của attributes
      $query->where('attributes', json_encode($attributes));
    } else {
      // Nếu không có attributes, kiểm tra null hoặc rỗng
      $query->whereNull('attributes');
    }

    $existing = $query->first();

    if ($existing) {
      // Nếu đã có trong giỏ, tăng số lượng lên 1
      DB::table('carts')->where('id', $existing->id)->increment('quantity');
    } else {
      // Lấy giá
      $price = 0;
      if ($bookFormatId) {
        $price = DB::table('book_formats')->where('id', $bookFormatId)->value('price');
      }
      if (!$price) {
        $price = DB::table('book_formats')->where('book_id', $bookId)->orderBy('price', 'asc')->value('price') ?? 0;
      }

      DB::table('carts')->insert([
        'id' => (string) \Illuminate\Support\Str::uuid(),
        'user_id' => $userId,
        'book_id' => $bookId,
        'book_format_id' => $bookFormatId,
        'attributes' => $attributes ? json_encode($attributes) : null,
        'quantity' => 1,
        'price_at_addition' => $price,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    return response()->json(['success' => true, 'message' => 'Đã thêm sản phẩm vào giỏ hàng']);
  }
}
