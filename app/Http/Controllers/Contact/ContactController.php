<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class ContactController extends Controller
{
  public function showForm()
  {
    return view('contact.contact');
  }

  public function submitForm(Request $request)
  {
    // Validate dữ liệu form, thêm address và note optional
    $data = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email',
      'phone' => 'required|string|max:20',
      'address' => 'nullable|string|max:255',
      'note' => 'nullable|string',
      // 'message' => 'required|string', 
    ]);

    // Nếu bạn muốn lưu message vào note, gán như sau:
    // $note = $data['note'] ?? $data['message'] ?? null;

    // Xóa liên hệ cũ nếu email đã tồn tại
    DB::table('contacts')->where('email', $data['email'])->delete();

    DB::table('contacts')->insert([
      'id' => (string) \Illuminate\Support\Str::uuid(), // Tạo UUID cho id
      'name' => $data['name'],
      'email' => $data['email'],
      'phone' => $data['phone'],
      'address' => $data['address'] ?? null,
      'note' => $data['note'] ?? null,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Gửi mail trong queue
    $fromAddress = config('mail.from.address');
    $fromName = config('mail.from.name');

    Queue::push(function () use ($data, $fromAddress, $fromName) {
      Mail::raw('Cảm ơn bạn đã góp ý cho chúng tôi!', function ($mail) use ($data, $fromAddress, $fromName) {
        $mail->from($fromAddress, $fromName)
          ->to($data['email'])
          ->subject('Cảm ơn bạn đã liên hệ BookBee!');
      });
    });

    return back()->with('success', 'Gửi liên hệ thành công! Email xác nhận sẽ được gửi đến bạn trong giây lát.');
  }
}
