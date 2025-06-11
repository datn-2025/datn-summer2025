<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminChatRealTimeController extends Controller
{
    public function index()
    {
        return view('admin.chat-real-time.index');
    }

    public function store(Request $request)
    {
        // Logic to handle storing chat messages
        // This could involve saving to a database or broadcasting to a channel
        return response()->json(['message' => 'Chat message stored successfully']);
    }
}
