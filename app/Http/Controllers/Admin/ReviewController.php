<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['book', 'user'])
            ->latest()
            ->paginate(10);
            
        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Review $review, $status)
    {
        $review->update(['status' => $status]);
        return back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function updateResponse(Review $review, Request $request)
    {
        $request->validate([
            'admin_response' => 'nullable|string|max:1000'
        ]);

        $review->update([
            'admin_response' => $request->admin_response
        ]);

        return back()->with('success', 'Cập nhật phản hồi thành công');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Xóa đánh giá thành công');
    }
}