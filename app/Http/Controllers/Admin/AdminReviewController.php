<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    // Hiển thị danh sách đánh giá
    public function index(Request $request)
    {
        $reviews = Review::with(['book', 'user'])
            // Tìm kiếm trạng thái đánh giá (Hiển thị/Ẩn)
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))

            // Tìm kiếm trạng thái phản hồi của admin (Đã/Chưa phản hồi)
            ->when($request->filled('admin_response'), function ($query) use ($request) {
                if ($request->admin_response === 'responded') {
                    return $query->whereNotNull('admin_response');
                } elseif ($request->admin_response === 'not_responded') {
                    return $query->whereNull('admin_response');
                }
            })

            // Tìm kiếm theo tên sản phẩm
            ->when($request->filled('product_name'), fn($q) => $q->whereHas('book', fn($q) => $q->where('title', 'like', '%' . $request->product_name . '%')))

            // Tìm kiếm theo tên khách hàng
            ->when($request->filled('customer_name'), fn($q) => $q->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->customer_name . '%')))

            // Tìm kiếm theo số sao đánh giá
            ->when($request->filled('rating'), fn($q) => $q->where('rating', $request->rating))

            // Gộp tìm kiếm bình luận khách hàng và phản hồi admin
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('comment', 'like', '%' . $request->keyword . '%')
                        ->orWhere('admin_response', 'like', '%' . $request->keyword . '%');
                });
            })

            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    // Cập nhật trạng thái (Ẩn/Hiện) của đánh giá
    public function updateStatus(Request $request, Review $review)
    {
        // Xoay vòng giữa hai trạng thái 'visible' và 'hidden'
        $newStatus = $review->status === 'visible' ? 'hidden' : 'visible';

        // Cập nhật trạng thái cho đánh giá
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
        // Kiểm tra xem đã có phản hồi chưa
        if ($review->admin_response) {
            return redirect()->back()
                ->with('error', 'Đánh giá này đã có phản hồi.');
        }

        // Kiểm tra tính hợp lệ của phản hồi
        $request->validate([
            'admin_response' => 'required|string|max:1000'
        ]);

        // Cập nhật phản hồi của admin và thay đổi trạng thái đánh giá thành "approved"
        $review->update([
            'admin_response' => $request->admin_response,
        ]);

        return redirect()->route('admin.reviews.response', $review)
            ->with('success', 'Đã gửi phản hồi thành công');
    }
}
