<?php

namespace App\Http\Controllers\Api;

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

class SupportController extends Controller
{
    /**
     * Send a support message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        return DB::transaction(function () use ($request, $user) {
            // Support conversations are always between a user and the first admin found
            // In a more complex system, we might have a dedicated support user or rotate assignments
            $admin = User::role('admin')->first();

            if (!$admin) {
                return response()->json(['message' => 'Support is currently unavailable.'], 503);
            }

            $conversation = Conversation::create([
                'user_one_id' => min($user->id, $admin->id),
                'user_two_id' => max($user->id, $admin->id),
                'is_support' => true,
                'subject' => $request->subject,
            ]);

            $message = $conversation->messages()->create([
                'sender_id' => $user->id,
                'body' => $request->message,
            ]);

            // Notify admin via email
            $supportEmail = config('mail.from.address'); // Or from settings if implemented
            try {
                Mail::to($supportEmail)->send(new SupportNotification($user, $message, 'new_support_ticket'));
            } catch (\Exception $e) {
                // Log error but don't fail the request
                Log::error("Failed to send support notification: " . $e->getMessage());
            }

            return response()->json([
                'message' => 'Support message sent successfully.',
                'data' => $message->load('conversation'),
            ], 201);
        });
    }
}
