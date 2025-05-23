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
}
