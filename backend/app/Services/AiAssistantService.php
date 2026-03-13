<?php

namespace App\Services;

use App\Models\AiChat;
use App\Models\AiMessage;
use OpenAI\Laravel\Facades\OpenAI;

class AiAssistantService
{
    protected $systemPrompt = "You are the GoPathway AI Travel Assistant. Your goal is to help users with travel, relocation, and immigration-related questions ONLY.\n\n" .
        "STRICT GUARDRAILS:\n" .
        "1. Do not answer questions about medical, legal (outside immigration/visa context), investment, or non-travel topics.\n" .
        "2. If a user asks something unrelated, politely steer them back to travel and immigration.\n" .
        "3. If the user's destination or pathway is known, tailor your advice to that specific context.\n" .
        "4. Keep answers professional, helpful, concise, and premium.\n" .
        "5. IMPORTANT: Do not provide legal advice. Remind users that regulations change and they should consult official government websites.";

    /**
     * Send a message to OpenAI and get a response.
     */
    public function getResponse(AiChat $chat, string $userMessage)
    {
        // 0. Safety/Prompt Injection Check
        if ($this->isMalicious($userMessage)) {
            \App\Helpers\Security::log('ai_threat_detected', 'high', 'Potential prompt injection or malicious query blocked.', ['message' => $userMessage], $chat->user_id);
            throw new \Exception("Your message contains restricted content or patterns. Please rephrase your question.");
        }

        // 1. Save user message
        AiMessage::create([
            'ai_chat_id' => $chat->id,
            'role' => 'user',
            'content' => $userMessage,
        ]);

        // 2. Build conversation history
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt],
        ];

        // Add last 10 messages for context
        $history = $chat->messages()->orderBy('created_at', 'desc')->limit(10)->get()->reverse();

        foreach ($history as $msg) {
            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }

        // 3. Call OpenAI
        try {
            // Using the Laravel OpenAI Wrapper
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000,
            ]);

            $aiResponse = $result->choices[0]->message->content;
            $tokensUsed = $result->usage->totalTokens;

            // 4. Save assistant response
            $savedMessage = AiMessage::create([
                'ai_chat_id' => $chat->id,
                'role' => 'assistant',
                'content' => $aiResponse,
                'tokens_used' => $tokensUsed,
            ]);

            return $savedMessage;

        }
        catch (\Exception $e) {
            \Log::error('OpenAI Error: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function isMalicious(string $input): bool
    {
        $input = strtolower($input);

        $forbiddenPatterns = [
            'ignore previous instructions',
            'ignore all instructions',
            'system prompt',
            'you are now',
            'dan mode',
            'jailbreak',
            'sql injection',
            'drop table',
            '<script>',
                'javascript:',
                    '---',
        ];

                foreach($forbiddenPatterns as $pattern) {
                    if (str_contains($input, $pattern)) {
                        return true;
                    }
                }

                if (strlen($input) > 2000) {
                    return true;
                }

                return false;
    }
}