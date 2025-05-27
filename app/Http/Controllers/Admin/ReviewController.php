<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['book', 'user'])
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('customer_name'), function($q) use ($request) {
                $q->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->customer_name . '%');
                });
            })
            ->when($request->filled('product_name'), function($q) use ($request) {
                $q->whereHas('book', function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->product_name . '%');
                });
            });

        $reviews = $query->latest()->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }
    // public function updateStatus(Review $review, $status)
    // {
    //     $review->update(['status' => $status]);
    //     return back()->with('success', 'Cập nhật trạng thái thành công');
    // }

    // public function updateResponse(Review $review, Request $request)
    // {
    //     $request->validate([
    //         'admin_response' => 'nullable|string|max:1000'
    //     ]);

    //     $review->update([
    //         'admin_response' => $request->admin_response
    //     ]);

    //     return back()->with('success', 'Cập nhật phản hồi thành công');
    // }

    // public function destroy(Review $review)
    // {
    //     $review->delete();
    //     return back()->with('success', 'Xóa đánh giá thành công');
    // }

    public function showResponseForm(Review $review)
    {
        $review->load(['book', 'user', 'book.author']);
        
        // Lấy các đánh giá khác của cùng sản phẩm
        $otherReviews = Review::where('book_id', $review->book_id)
            ->where('id', '!=', $review->id)
            ->with(['user' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(5);
        
        return view('admin.reviews.response', compact('review', 'otherReviews'));
    }

    public function storeResponse(Request $request, Review $review)
    {
        if ($review->admin_response) {
            return redirect()->back()
                ->with('error', 'Đã có phản hồi cho đánh giá này');
        }

        $request->validate([
            'admin_response' => 'required|string|max:1000'
        ]);

        $review->update([
            'admin_response' => $request->admin_response,
            'status' => 'approved'
        ]);

        return redirect()->route('admin.reviews.response', $review)
            ->with('success', 'Đã gửi phản hồi thành công');
    }
}