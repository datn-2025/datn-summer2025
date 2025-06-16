<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use PDF;
use Brian2694\Toastr\Facades\Toastr;

class AdminInvoiceController extends Controller
{    public function index(Request $request)
    {        $query = Invoice::query();
        $query->with([
            'order' => function($q) {
                $q->with(['user', 'address', 'paymentMethod']);
            },
            'items' => function($q) {
                $q->with(['book', 'book.author']);
            }
        ]);

        // Tìm kiếm theo mã đơn hàng
        if ($request->filled('search_order_code')) {
            $orderCode = $request->search_order_code;
            $query->whereHas('order', function($q) use ($orderCode) {
                $q->where('order_code', 'like', '%' . $orderCode . '%');
            });
        }

        // Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $paymentStatus = $request->payment_status;
            $query->whereHas('order', function($q) use ($paymentStatus) {
                $q->where('payment_status', $paymentStatus);
            });
        }        // Lọc theo phương thức thanh toán
        if ($request->filled('payment_method')) {
            $paymentMethodId = $request->payment_method;
            $query->whereHas('order', function($q) use ($paymentMethodId) {
                $q->where('payment_method_id', $paymentMethodId);
            });
        }

        // Lấy danh sách phương thức thanh toán từ bảng payment_methods
        $paymentMethods = \App\Models\PaymentMethod::all();

        $invoices = $query->latest()
            ->paginate(10)
            ->withQueryString();
        
        return view('admin.invoices.index', compact('invoices', 'paymentMethods'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['order', 'order.user', 'items.book'])
            ->findOrFail($id);
        
        return view('admin.invoices.show', compact('invoice'));
    }

    public function generatePdf($id)
    {
        $invoice = Invoice::with(['order', 'order.user', 'items.book'])
            ->findOrFail($id);

        $pdf = PDF::loadView('admin.invoices.pdf', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->order->order_code . '.pdf');
    }
}
