<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Voucher;
use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use App\Models\PaymentMethod;
use App\Services\OrderService;
use App\Services\VoucherService;
use App\Services\PaymentService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $orderService;
    protected $voucherService;
    protected $paymentService;
    protected $emailService;

    public function __construct(
        OrderService $orderService,
        VoucherService $voucherService,
        PaymentService $paymentService,
        EmailService $emailService
    ) {
        $this->orderService = $orderService;
        $this->voucherService = $voucherService;
        $this->paymentService = $paymentService;
        $this->emailService = $emailService;
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        $vouchers = $this->voucherService->getAvailableVouchers($user);
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // Lấy thông tin giỏ hàng
        $cartItems = $user->cart()->with(['book.images', 'bookFormat'])->get();

        // Tính tổng tiền
        $subtotal = $cartItems->sum('price');

        return view('orders.checkout', compact(
            'addresses',
            'vouchers',
            'paymentMethods',
            'cartItems',
            'subtotal'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'voucher_code' => 'nullable|exists:vouchers,code',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'shipping_method' => 'required|in:standard,express',
            'note' => 'nullable|string|max:500'
        ]);

        Log::info('Validation passed in OrderController::store.');

        try {
            DB::beginTransaction();
            Log::info('DB Transaction started.');

            $user = Auth::user();

            // Lấy thông tin giỏ hàng của người dùng
            $cartItems = $user->cart()->with(['book.images', 'bookFormat'])->get();
            Log::info('Cart items retrieved. Count: ' . $cartItems->count());

            // Kiểm tra giỏ hàng có trống không
            if ($cartItems->isEmpty()) {
                Log::warning('Cart is empty during checkout.');
                return redirect()->back()->with('error', 'Giỏ hàng của bạn đang trống.');
            }

            // Tính phí vận chuyển
            $shippingFee = $request->shipping_method === 'standard' ? 20000 : 40000;
            Log::info('Shipping fee calculated: ' . $shippingFee);

            // Lấy trạng thái đơn hàng và thanh toán
            $orderStatus = OrderStatus::where('name', 'Chờ xác nhận')->first();
            $paymentStatus = PaymentStatus::where('name', 'Chờ thanh toán')->first();

            if (!$orderStatus || !$paymentStatus) {
                throw new \Exception('Không tìm thấy trạng thái đơn hàng hoặc thanh toán');
            }

            // Tạo đơn hàng
            Log::info('Calling OrderService::createOrder.');
            $order = $this->orderService->createOrder([
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'voucher_code' => $request->voucher_code,
                'payment_method_id' => $request->payment_method_id,
                'shipping_method' => $request->shipping_method,
                'shipping_fee' => $shippingFee,
                'note' => $request->note,
                'cart_items' => $cartItems,
                'order_status_id' => $orderStatus->id,
                'payment_status_id' => $paymentStatus->id
            ]);
            Log::info('OrderService::createOrder completed. Order ID: ' . $order->id);

            // Xử lý thanh toán
            Log::info('Calling PaymentService::createPayment.');
            $payment = $this->paymentService->createPayment([
                'order_id' => $order->id,
                'payment_method_id' => $request->payment_method_id,
                'amount' => $order->total_amount
            ]);
            Log::info('PaymentService::createPayment completed. Payment ID: ' . $payment->id);

            DB::commit();
            Log::info('DB Transaction committed.');

            // Gửi email xác nhận qua queue
            dispatch(function() use ($order) {
                $this->emailService->sendOrderConfirmation($order);
            })->afterCommit();

            Log::info('Redirecting to orders.show.');
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Đặt hàng thành công');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này');
        }

        $order->load([
            'orderItems.book.images',
            'orderItems.bookFormat',
            'orderStatus',
            'paymentStatus',
            'payments.paymentMethod',
            'address',
            'user'
        ]);

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with(['orderStatus', 'paymentStatus'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|exists:vouchers,code',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $voucher = Voucher::where('code', $request->voucher_code)->first();
        $discount = $this->voucherService->calculateDiscount($voucher, $request->subtotal);

        return response()->json([
            'success' => true,
            'discount' => $discount
        ]);
    }
}
