<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $conversationId = $request->input('conversation_id');
    // dd($request->all());
    if (!$conversationId) {
        return response()->json(['message' => 'Missing conversation ID'], 400);
    }

    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return response()->json(['message' => 'Conversation not found'], 404);
    }

    $adminId = 'bc70a8f8-2d85-4a84-8d1d-f9e812c13fd4';

    // So sánh id về cùng kiểu string để tránh lỗi kiểu dữ liệu
    if ((string)$conversation->customer_id !== (string)$user->id && (string)$user->id !== (string)$adminId) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    // Lấy danh sách tin nhắn
    $messages = $conversation->messages()
        ->with('sender')
        ->orderBy('created_at', 'asc')
        ->get();

    // dd($messages);
    return response()->json($messages);
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'nullable|string',
            'type' => 'nullable|string',
            'file_path' => 'nullable|string',
        ]);
        $conversation = Conversation::find($request->conversation_id);
        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found'], 404);
        }
        $adminId = 'bc70a8f8-2d85-4a84-8d1d-f9e812c13fd4';
        if ($conversation->customer_id !== $user->id && $user->id !== $adminId) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'content' => $request->content,
            'type' => $request->type ?? 'text',
            'file_path' => $request->file_path,
        ]);
        $message->load('sender');
        return response()->json($message, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
