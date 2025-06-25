<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\OrderStatusHelper;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\User;
use App\Models\Address;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Jobs\SendOrderStatusUpdatedMail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'address', 'orderStatus', 'paymentStatus'])
            ->orderBy('created_at', 'desc');
        $orderStatuses = OrderStatus::query()->get();
        $paymentStatuses = PaymentStatus::query()->get();
        // tìm kiếm đơn hàng
        // dd($request->all());
        if ($request->has('search') ) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->has('status')) {
            $query->whereHas('orderStatus', function ($q) use ($request) {
                $q->where('name', $request->status);
            });
        }
        if ($request->has('payment')) {
            $query->whereHas('paymentStatus', function ($q) use ($request) {
                $q->where('name', $request->payment);
            });
        }
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->paginate(10);

        // lấy ra số đơn hàng theo trạng thái
        $orderCounts = [
            'total' => Order::count(),
            'Chờ Xác Nhận' => Order::whereHas('orderStatus', function ($q) {
                $q->where('name', 'Chờ Xác Nhận');
            })->count(),
            'Đã Giao Thành Công' => Order::whereHas('orderStatus', function ($q) {
                $q->where('name', 'Đã Giao Thành Công');
            })->count(),
            'Đã Hủy' => Order::whereHas('orderStatus', function ($q) {
                $q->where('name', 'Đã Hủy');
            })->count(),
        ];

        return view('admin.orders.index', compact('orders', 'orderCounts', 'orderStatuses', 'paymentStatuses'));
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'address',
            'orderStatus',
            'paymentStatus',
            'voucher',
            'payments',
            'invoice',
        ])->findOrFail($id);

        // Get order items with their attribute values
        $orderItems = OrderItem::where('order_id', $id)
            ->with(['book', 'attributeValues.attribute', 'bookFormat'])
            ->get();
        foreach ($orderItems as $item) {
            $bookFormat = optional($item->bookFormat)->format_name;  // Safely access 'name' of 'bookFormat'
        }
        // dd($bookFormat);
        // Generate QR code if not exists
        // if (!$order->qr_code) {
        //     $this->generateQrCode($order);
        // }

        return view('admin.orders.show', compact('order', 'orderItems', 'bookFormat'));
    }

    public function edit($id)
    {
        $order = Order::with(['user', 'address', 'orderStatus', 'paymentStatus'])->findOrFail($id);
        $currentStatus = $order->orderStatus->name;
        $allowedNames = OrderStatusHelper::getNextStatuses($currentStatus);
        $orderStatuses = OrderStatus::whereIn('name', $allowedNames)->get();
        $paymentStatuses = PaymentStatus::all();

        // Generate QR code if not exists
        // if (!$order->qr_code) {
        //     $this->generateQrCode($order);
        // }

        return view('admin.orders.edit', compact('order', 'orderStatuses', 'paymentStatuses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
            'payment_status_id' => 'required|exists:payment_statuses,id',
            'cancellation_reason' => 'nullable|string|max:255', // Thêm validation cho lý do hủy
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $currentStatus = $order->orderStatus->name;
            $newStatus = OrderStatus::findOrFail($request->order_status_id)->name;
            $allowed = OrderStatusHelper::getNextStatuses($currentStatus);

            if (!in_array($newStatus, $allowed)) {
                Toastr::error("Trạng thái mới không hợp lệ với trạng thái hiện tại", 'Lỗi');
                return back()->withInput();
            }

            $updateData = [
                'order_status_id' => $request->order_status_id,
                'payment_status_id' => $request->payment_status_id,
            ];

            // Nếu trạng thái là 'Đã Hủy' và chưa có ngày hủy, cập nhật thông tin hủy
            if ($newStatus == 'Đã Hủy' && is_null($order->cancelled_at)) {
                $updateData['cancelled_at'] = now();
                $updateData['cancellation_reason'] = $request->cancellation_reason;
            }

            $order->update($updateData);

            Log::info("Order {$order->id} status changed from {$currentStatus} to {$newStatus} by admin");

            DB::commit();

            dispatch(new SendOrderStatusUpdatedMail($order, $newStatus));

            Toastr::success('Cập nhật trạng thái đơn hàng thành công', 'Thành công');
            return redirect()->route('admin.orders.show', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order status: ' . $e->getMessage());

            Toastr::error('Lỗi khi cập nhật trạng thái đơn hàng. Vui lòng thử lại.', 'Lỗi');
            return back();
        }
    }


    //    tạo qr cho đơn hàng
    // private function generateQrCode(Order $order)
    // {
    //     try {
    //         // Create QR code with order information
    //         $orderInfo = [
    //             'id' => $order->id,
    //             'customer' => $order->user->name ?? 'N/A',
    //             'total' => $order->total_amount,
    //             'date' => $order->created_at->format('Y-m-d H:i:s')
    //         ];

    //         $qrCode = QrCode::format('png')
    //             ->size(200)
    //             ->errorCorrection('H')
    //             ->generate(json_encode($orderInfo));

    //         $filename = 'order_qr/order_' . substr($order->id, 0, 8) . '.png';
    //         Storage::disk('public')->put($filename, $qrCode);

    //         // Update order with QR code path
    //         $order->update(['qr_code' => $filename]);

    //     } catch (\Exception $e) {
    //         Log::error('Error generating QR code: ' . $e->getMessage());
    //     }
    // }

    public function cancelled(Request $request)
    {
        // Bắt đầu với các đơn hàng đã bị hủy
        $query = Order::whereHas('orderStatus', function ($q) {
            $q->where('name', 'Đã Hủy');
        })->with(['user', 'address', 'orderStatus', 'paymentStatus'])
          ->orderBy('created_at', 'desc');

        $orderStatuses = OrderStatus::query()->get();
        $paymentStatuses = PaymentStatus::query()->get();

        // Áp dụng bộ lọc tìm kiếm nếu có
        if ($request->has('search') ) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        if ($request->has('payment')) {
            $query->whereHas('paymentStatus', function ($q) use ($request) {
                $q->where('name', $request->payment);
            });
        }
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->paginate(10);

        // Lấy số lượng đơn hàng, tương tự như trong index
        $cancelledCustomerCount = Order::whereHas('orderStatus', function ($q) {
            $q->where('name', 'Đã Hủy');
        })->distinct('user_id')->count('user_id');

        $orderCounts = [
            'total' => Order::count(),
            'Chờ Xác Nhận' => Order::whereHas('orderStatus', function ($q) {
                $q->where('name', 'Chờ Xác Nhận');
            })->count(),
            'Đã Giao Thành Công' => Order::whereHas('orderStatus', function ($q) {
                $q->where('name', 'Đã Giao Thành Công');
            })->count(),
            'Đã Hủy' => Order::whereHas('orderStatus', function ($q) {
                $q->where('name', 'Đã Hủy');
            })->count(),
            'cancelled_customers' => $cancelledCustomerCount,
        ];

        return view('admin.orders.cancelled', compact('orders', 'orderCounts', 'orderStatuses', 'paymentStatuses'));
    }
}
