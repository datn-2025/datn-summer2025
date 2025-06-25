<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\Review;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ReviewClientController extends Controller
{
     public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem đánh giá');
        }
    
        $user = Auth::user();
        $type = $request->query('type', '1');
        
        $completedStatus = OrderStatus::where('name', 'Thành công')->first();
        
        if (!$completedStatus) {
            return redirect()->back()->with('error', 'Không tìm thấy trạng thái đơn hàng đã hoàn thành');
        }
        
        $query = $user->orders()
            ->with(['orderItems.book', 'reviews'])
            ->where('order_status_id', $completedStatus->id);
        
        switch ($type) {
            case '2': 
                $query->whereDoesntHave('reviews');
                break;
            case '3':
                $query->whereHas('reviews');
                break;
        }
        
        // Sắp xếp: đơn hàng chưa đánh giá lên đầu, sau đó mới đến đơn hàng đã đánh giá
        $orders = $query->select('orders.*')
            ->leftJoin('reviews', function($join) {
                $join->on('orders.id', '=', 'reviews.order_id')
                     ->whereNull('reviews.deleted_at');
            })
            ->groupBy('orders.id')
            ->orderByRaw('COUNT(reviews.id) = 0 DESC') // Sắp xếp đơn hàng chưa đánh giá lên đầu
            ->latest('orders.created_at') // Sau đó sắp xếp theo thời gian tạo mới nhất
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
            // comment có thể null nếu là đánh giá nhanh
            'comment' => 'nullable|string|max:1000',
        ], [
            'order_id.required' => 'Đơn hàng không hợp lệ',
            'book_id.required' => 'Sách không hợp lệ',
            'rating.required' => 'Đánh giá không hợp lệ',
            'rating.min' => 'Đánh giá phải từ 1 đến 5',
            'rating.max' => 'Đánh giá phải từ 1 đến 5',
            'comment.max' => 'Nội dung đánh giá không hợp lệ'
        ]);

        $user = Auth::user();
        $order = $user->orders()->where('id', $request->order_id)
            ->whereHas('orderStatus', function($q) {
                $q->where('name', 'Thành công');
            })->firstOrFail();
        $order->orderItems()->where('book_id', $request->book_id)->firstOrFail();

        // Kiểm tra đã đánh giá chưa (kể cả đã xóa mềm)
        $existingReview = Review::withTrashed()
            ->where('user_id', $user->id)
            ->where('book_id', $request->book_id)
            ->where('order_id', $order->id)
            ->first();
        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi');
        }

        Review::create([
            'user_id' => $user->id,
            'book_id' => $request->book_id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
    
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }
        $timeLimit = $review->created_at->addHours(24);
        if (now()->gt($timeLimit)) {
            return redirect()->route('account.purchase')->with('error', 'Chỉ có thể cập nhật đánh giá trong vòng 24 giờ');
        }
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);
        // Nếu không thay đổi gì thì thông báo info và quay lại danh sách
        if ($review->comment === $validated['comment']) {
            return redirect()->route('account.purchase')->with('info', 'Không có thay đổi nào cho đánh giá.');
        }
        $review->update([
            'comment' => $validated['comment'],
        ]);
        return redirect()->route('account.purchase')->with('success', 'Cập nhật đánh giá thành công');
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
        
        return redirect()->back()->with('success', 'Xóa đánh giá thành công');
    }
}
