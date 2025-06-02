<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientReviewController extends Controller
{
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        
        // Kiểm tra quyền sở hữu
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Kiểm tra thời gian (24h)
        $timeLimit = $review->created_at->addHours(24);
        if (now()->gt($timeLimit)) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể cập nhật đánh giá trong vòng 24 giờ'
            ], 403);
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
        
        $review->update($validated);
        
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Cập nhật đánh giá thành công'
        // ]);
        return redirect()->back()->with('success', 'Cập nhật đánh giá thành công');
    }
    
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        // Kiểm tra quyền sở hữu
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Kiểm tra thời gian (7 ngày)
        $timeLimit = $review->created_at->addDays(7);
        if (now()->gt($timeLimit)) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể xóa đánh giá trong vòng 7 ngày'
            ], 403);
        }
        
        $review->delete();
        
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Đã xóa đánh giá'
        // ]);
        return redirect()->back()->with('success', 'Xóa đánh giá thành công');
    }
}