<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SopAiReviewService
{
    /**
     * The OpenAI API key.
     */
    protected string $apiKey;

    public function __construct()
    {
        // Try getting OpenAI key, if not, fallback to null
        $this->apiKey = env('OPENAI_API_KEY', '');
    }

    /**
     * Submit an SOP draft for an AI-powered review.
     * Returns structured feedback.
     */
    public function reviewDraft(string $country, string $visaType, string $draft): array
    {
        if (empty($this->apiKey)) {
            // Fallback for local dev/testing if no API key is set
            return $this->getMockReview($country, $visaType);
        }

        try {
            return $this->callOpenAI($country, $visaType, $draft);
        } catch (Exception $e) {
            Log::error("SOP AI Review Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Failed to generate AI review. Please try again later.',
                'suggestions' => []
            ];
        }
    }

    /**
     * Make an actual call to the OpenAI API using gpt-4o or gpt-3.5-turbo.
     */
    protected function callOpenAI(string $country, string $visaType, string $draft): array
    {
        $prompt = <<<EOT
You are an expert immigration consultant. Review the following Statement of Purpose (SOP) draft for a {$visaType} application to {$country}.
Analyze the draft for:
1. Clarity and structure
2. Persuasiveness of intent
3. Red flags that might lead to a visa rejection

Provide constructive suggestions for improvement. Return the response strictly as a JSON object with this shape:
{
    "overall_feedback": "A short summary paragraph of the draft.",
    "strengths": ["Strength 1", "Strength 2"],
    "weaknesses": ["Weakness 1", "Weakness 2"],
    "actionable_tips": ["Tip 1", "Tip 2"]
}

Draft:
---
{$draft}
---
EOT;

        $response = Http::withToken($this->apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o', // or 'gpt-3.5-turbo' if you prefer lower cost
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '{}';
            $parsed = json_decode($content, true);

            return [
                'success' => true,
                'data' => $parsed
            ];
        }

        throw new Exception("OpenAI API returned status " . $response->status());
    }

    /**
     * Return mock data if no API key is provided, so the frontend UI can still be tested.
     */
    protected function getMockReview(string $country, string $visaType): array
    {
        return [
            'success' => true,
            'is_mock' => true,
            'data' => [
                'overall_feedback' => "[MOCK] This is a strong starting draft for your {$visaType} to {$country}. You clearly state your goals, but could tie them more directly to your long-term career intent.",
                'strengths' => [
                    "Clear academic history stated.",
                    "Enthusiasm for the destination country is evident."
                ],
                'weaknesses' => [
                    "Lack of specific details on how this visa aligns with your 5-year plan.",
                    "Some sentences are overly long and hard to follow."
                ],
                'actionable_tips' => [
                    "Break your second paragraph into two separate ideas.",
                    "Explicitly state why you chose this specific institution or program rather than just the country generally.",
                    "Add a concluding paragraph summarizing your primary intent to comply with visa regulations."
                ]
            ]
        ];
    }
}
