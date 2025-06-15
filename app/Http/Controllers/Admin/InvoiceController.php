<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Brian2694\Toastr\Facades\Toastr;

class InvoiceController extends Controller
{
  public function index(Request $request)
  {
    $query = Invoice::query();

    // Eager loading các mối quan hệ cần thiết
    $query->with([
      'order' => function ($q) {
        $q->with(['user', 'address', 'paymentMethod', 'paymentStatus']);
      },
      'items' => function ($q) {
        $q->with(['book', 'book.author']);
      }
    ]);

    // Tìm kiếm theo mã đơn hàng
    if ($request->filled('order_code')) {
      $orderCode = $request->order_code;
      $query->whereHas('order', function ($q) use ($orderCode) {
        $q->where('order_code', 'like', "%{$orderCode}%");
      });
    }

    // Tìm kiếm theo tên khách hàng
    if ($request->filled('customer_name')) {
      $customerName = $request->customer_name;
      $query->whereHas('order.user', function ($q) use ($customerName) {
        $q->where('name', 'like', "%{$customerName}%");
      });
    }

    // Tìm kiếm theo email khách hàng
    if ($request->filled('customer_email')) {
      $customerEmail = $request->customer_email;
      $query->whereHas('order.user', function ($q) use ($customerEmail) {
        $q->where('email', 'like', "%{$customerEmail}%");
      });
    }

    // Lọc theo trạng thái thanh toán
    if ($request->filled('payment_status')) {
      $paymentStatus = $request->payment_status;
      $query->whereHas('order', function ($q) use ($paymentStatus) {
        if ($paymentStatus === 'paid') {
          $q->where('payment_status_id', 2); // 2 = Đã thanh toán
        } else {
          $q->where('payment_status_id', 1); // 1 = Chưa thanh toán
        }
      });
    }        // Sắp xếp theo thời gian tạo mới nhất
    $query->orderBy('created_at', 'desc');

    // Phân trang kết quả
    $invoices = $query->paginate(15)->withQueryString();

    // Trả về view với dữ liệu
    return view('admin.invoices.index', compact('invoices'));
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
