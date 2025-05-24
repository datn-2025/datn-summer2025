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
        ->where('wishlists.user_id', $userId)
        ->select(
          'books.id as book_id',
          'books.cover_image',
          'books.title',
          'authors.name as author_name',
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
}
