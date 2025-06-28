<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['book', 'user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when(
                $request->admin_response,
                fn($q) =>
                $q->when($request->admin_response === 'responded', fn($q) => $q->whereNotNull('admin_response'))
                    ->when($request->admin_response === 'not_responded', fn($q) => $q->whereNull('admin_response'))
            )
            ->when(
                $request->product_name,
                fn($q) =>
                $q->whereHas('book', fn($bq) => $bq->where('title', 'like', '%' . $request->product_name . '%'))
            )
            ->when(
                $request->customer_name,
                fn($q) =>
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', '%' . $request->customer_name . '%'))
            )
            ->when($request->rating, fn($q) => $q->where('rating', $request->rating))
            ->when(
                $request->cmt,
                fn($q) =>
                $q->where(
                    fn($q) =>
                    $q->where('comment', 'like', '%' . $request->cmt . '%')
                        ->orWhere('admin_response', 'like', '%' . $request->cmt . '%')
                )
            )
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        if (!in_array($review->status, ['visible', 'hidden'])) {
            Toastr::error('Trạng thái không hợp lệ', 'Lỗi');
            return redirect()->route('admin.reviews.index');
        }

        $newStatus = $review->status === 'visible' ? 'hidden' : 'visible';
        $review->update(['status' => $newStatus]);

        Toastr::success('Cập nhật trạng thái đánh giá thành công', 'Thành công');
        return redirect()->route('admin.reviews.index');
    }


    // Hiển thị form phản hồi hoặc chỉnh sửa phản hồi của admin
    public function showResponseForm(Review $review)
    {
        $review->load([
            'book' => function ($q) {
                $q->withCount('reviews') // Tổng số đánh giá
                    ->withAvg('reviews', 'rating') // Trung bình sao
                    ->withSum('orderItems as sold_count', 'quantity') // Tổng đã bán
                    ->with(['author', 'brand', 'category']);
            },
            'user'
        ]);

        $otherReviews = Review::where('book_id', $review->book_id)
            ->where('id', '!=', $review->id)
            ->with(['user' => fn($query) => $query->withTrashed()])
            ->latest()
            ->paginate(5);

        return view('admin.reviews.response', compact('review', 'otherReviews'));
    }

    // Lưu phản hồi admin
    public function storeResponse(Request $request, Review $review)
    {
        if ($review->admin_response) {
            return redirect()->back()
                ->with('error', 'Đánh giá này đã có phản hồi và không thể chỉnh sửa.');
        }

        $request->validate([
            'admin_response' => 'required|string|max:1000'
        ]);

        $review->update([
            'admin_response' => $request->admin_response,
        ]);

        Toastr::success('Đã gửi phản hồi thành công.', 'Thành công');
        return redirect()->route('admin.reviews.index');
    }
}
