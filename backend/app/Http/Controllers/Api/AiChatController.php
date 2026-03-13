<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiChat;
use App\Services\AiAssistantService;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    protected $aiService;

    public function __construct(AiAssistantService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * List all AI chats for the user.
     */
    public function index(Request $request)
    {
        return response()->json([
            'data' => $request->user()->aiChats()->orderBy('updated_at', 'desc')->get()
        ]);
    }

    /**
     * Create a new chat session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
        ]);

        $chat = $request->user()->aiChats()->create([
            'title' => $validated['title'] ?? 'New Inquiry',
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Chat session created.',
            'data' => $chat
        ], 201);
    }

    /**
     * Show messages for a specific chat.
     */
    public function show(Request $request, AiChat $aiChat)
    {
        $this->authorizeAccess($request, $aiChat);

        return response()->json([
            'data' => $aiChat->load('messages')
        ]);
    }

    /**
     * Send a message to the AI.
     */
    public function sendMessage(Request $request, AiChat $aiChat)
    {
        $this->authorizeAccess($request, $aiChat);

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $aiResponse = $this->aiService . getResponse($aiChat, $validated['message']);

            return response()->json([
                'data' => $aiResponse
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => 'AI Assistant is currently unavailable. Please try again later.'
            ], 503);
        }
    }

    /**
     * Delete a chat session.
     */
    public function destroy(Request $request, AiChat $aiChat)
    {
        $this->authorizeAccess($request, $aiChat);
        $aiChat->delete();

        return response()->json([
            'message' => 'Chat history deleted.'
        ]);
    }

    protected function authorizeAccess(Request $request, AiChat $chat)
    {
        if ($chat->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized access to chat history.');
        }
    }
}