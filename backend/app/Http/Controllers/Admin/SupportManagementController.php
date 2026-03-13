<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Mail\SupportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupportManagementController extends Controller
{
    /**
     * List all support conversations.
     */
    public function index()
    {
        $conversations = Conversation::where('is_support', true)
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($conversation) {
                // For support, we usually want to know who the "customer" is
                // Assuming userOne or userTwo is the admin, the other is the customer
                $customer = $conversation->userOne->hasRole('admin') ? $conversation->userTwo : $conversation->userOne;
                
                return [
                    'id' => $conversation->id,
                    'subject' => $conversation->subject,
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                    ],
                    'last_message' => $conversation->lastMessage,
                    'updated_at' => $conversation->updated_at,
                ];
            });

        return response()->json(['data' => $conversations]);
    }

    /**
     * Show a support conversation.
     */
    public function show($id)
    {
        $conversation = Conversation::where('id', $id)
            ->where('is_support', true)
            ->firstOrFail();

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin's messages as read would be done on the user side, 
        // but here we mark user's messages as read by admin
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'data' => [
                'conversation' => $conversation->load(['userOne', 'userTwo']),
                'messages' => $messages,
            ]
        ]);
    }

    /**
     * Reply to a support message.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $conversation = Conversation::where('id', $id)
            ->where('is_support', true)
            ->firstOrFail();

        $admin = Auth::user();

        return DB::transaction(function () use ($conversation, $admin, $request) {
            $message = $conversation->messages()->create([
                'sender_id' => $admin->id,
                'body' => $request->body,
            ]);

            $conversation->touch();

            // Find the customer to notify them
            $customer = $conversation->userOne->id === $admin->id ? $conversation->userTwo : $conversation->userOne;

            /** @var User $admin */
            $admin = Auth::user();
            try {
                Mail::to($customer->email)->send(new SupportNotification($admin, $message, 'admin_reply'));
            } catch (\Exception $e) {
                Log::error("Failed to send support reply notification: " . $e->getMessage());
            }

            return response()->json([
                'message' => 'Reply sent successfully.',
                'data' => $message->load('sender'),
            ], 201);
        });
    }
}
