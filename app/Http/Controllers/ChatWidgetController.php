<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatWidgetController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return view('layouts.app'); // Không truyền biến gì nếu chưa login
        }
        $user = Auth::user();

        // Tìm hoặc tạo conversation mặc định giữa user và admin
        $adminId = 'bc70a8f8-2d85-4a84-8d1d-f9e812c13fd4';
        $conversation = Conversation::firstOrCreate([
            'customer_id' => $user->id,
            'admin_id' => $adminId
        ], [
            'id' => Str::uuid(), // 👈 THÊM DÒNG NÀY
        ]);

        // Lấy tin nhắn
        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();

        return view('layouts.app', [
            'selectedConversation' => $conversation,
            'messages' => $messages
        ]);
    }
}
