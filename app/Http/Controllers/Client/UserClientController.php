<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Toastr;
use Illuminate\Support\Facades\Log;

class UserClientController extends Controller
{
    // public function index(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem đánh giá');
    //     }

    //     $user = Auth::user();
    //     $type = $request->query('type', '1');

    //     // Get completed orders (assuming status 'Thành công' means completed)
    //     $completedStatus = OrderStatus::where('name', 'Thành công')->first();

    //     if (!$completedStatus) {
    //         return redirect()->back()->with('error', 'Không tìm thấy trạng thái đơn hàng đã hoàn thành');
    //     }

    //     $query = $user->orders()
    //         ->with(['orderItems.book', 'reviews'])
    //         ->where('order_status_id', $completedStatus->id);

    //     // Filter based on review status
    //     switch ($type) {
    //         case '2': // Not reviewed
    //             $query->whereDoesntHave('reviews');
    //             break;
    //         case '3': // Already reviewed
    //             $query->whereHas('reviews');
    //             break;
    //         // Default (type=1): Show all
    //     }

    //     $orders = $query->latest()->paginate(10);

    //     return view('clients.account.purchases', [
    //         'orders' => $orders,
    //         'currentType' => $type,
    //     ]);
    // }

    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem đánh giá');
        }

        $user = Auth::user();
        $type = $request->query('type', '1');

        // Get completed orders (assuming status 'Thành công' means completed)
        $completedStatus = OrderStatus::where('name', 'Thành công')->first();

        if (!$completedStatus) {
            return redirect()->back()->with('error', 'Không tìm thấy trạng thái đơn hàng đã hoàn thành');
        }

        $query = $user->orders()
            ->with(['orderItems.book', 'reviews'])
            ->where('order_status_id', $completedStatus->id);

        // Filter based on review status
        switch ($type) {
            case '2': // Not reviewed
                $query->whereDoesntHave('reviews');
                break;
            case '3': // Already reviewed
                $query->whereHas('reviews');
                break;
                // Default (type=1): Show all
        }

        // Sắp xếp: đơn hàng chưa đánh giá lên đầu, sau đó mới đến đơn hàng đã đánh giá
        $orders = Order::withCount(['reviews' => function ($query) {
            $query->whereNull('deleted_at');
        }])
            ->orderByRaw('reviews_count = 0 DESC') // Ưu tiên đơn hàng chưa có đánh giá
            ->latest('created_at') // Sau đó sắp xếp theo thời gian tạo mới nhất
            ->paginate(10);

        return view('clients.account.purchases', [
            'orders' => $orders,
            'currentType' => $type,
        ]);
    }

    public function storeReview(Request $request)
    {
        Log::info('Review data:', $request->all());
        Log::info('User ID:', ['user_id' => Auth::id()]);

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ], [
            'order_id.required' => 'Đơn hàng không hợp lệ',
            'book_id.required' => 'Sách không hợp lệ',
            'rating.required' => 'Đánh giá không hợp lệ',
            'comment.required' => 'Nội dung đánh giá không hợp lệ',
            'rating.min' => 'Đánh giá phải từ 1 đến 5',
            'rating.max' => 'Đánh giá phải từ 1 đến 5',
            'comment.max' => 'Nội dung đánh giá không hợp lệ'
        ]);

        $user = Auth::user();

        // Check if the order belongs to the user and is completed
        $order = $user->orders()
            ->where('id', $request->order_id)
            ->whereHas('orderStatus', function ($q) {
                $q->where('name', 'Thành công');
            })
            ->firstOrFail();

        // Check if the book is in the order
        $order->orderItems()
            ->where('book_id', $request->book_id)
            ->firstOrFail();

        // Check if review already exists
        // $existingReview = Review::where('user_id', $user->id)
        //     ->where('book_id', $request->book_id)
        //     ->where('order_id', $order->id)
        //     ->exists();

        // if ($existingReview) {
        //     return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi');
        // }
        $existingReview = Review::withTrashed()
            ->where('user_id', $user->id)
            ->where('book_id', $request->book_id)
            ->where('order_id', $order->id)
            ->first();

        if ($existingReview) {
            if ($existingReview->trashed()) {
                return redirect()->back()->with('error', 'Bạn đã xóa đánh giá cho sản phẩm này và không thể đánh giá lại');
            }
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi');
        }

        // Create the review
        Review::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'book_id' => $request->book_id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'approved', // or 'pending' if you want to moderate reviews
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}
