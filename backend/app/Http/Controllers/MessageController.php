<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $conversations = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->get()
            ->map(function ($conversation) use ($userId) {
                $otherUser = $conversation->user_one_id === $userId ? $conversation->userTwo : $conversation->userOne;
                return [
                    'id' => $conversation->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                    ],
                    'last_message' => $conversation->lastMessage,
                    'updated_at' => $conversation->updated_at,
                ];
            });

        return response()->json(['data' => $conversations]);
    }

    public function show($id)
    {
        $conversation = Conversation::where('id', $id)
            ->where(function ($query) {
                $query->where('user_one_id', Auth::id())
                    ->orWhere('user_two_id', Auth::id());
            })
            ->firstOrFail();

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['data' => $messages]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'body' => 'required|string',
        ]);

        $senderId = Auth::id();
        $recipientId = $request->recipient_id;

        if ($senderId == $recipientId) {
            return response()->json(['message' => 'You cannot message yourself'], 422);
        }

        // Find or create conversation
        $userOneId = min($senderId, $recipientId);
        $userTwoId = max($senderId, $recipientId);

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $senderId,
            'body' => $request->body,
        ]);

        $conversation->touch(); // Update updated_at for sorting

        return response()->json(['data' => $message->load('sender')], 201);
    }
}
