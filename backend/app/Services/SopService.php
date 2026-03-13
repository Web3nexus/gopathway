<?php

namespace App\Services;

use App\Models\SopDraft;
use App\Models\User;
use Illuminate\Support\Str;

class SopService
{
    /**
     * Generate SOP text based on draft answers.
     * Initially using a sophisticated template-based approach.
     */
    public function generate(SopDraft $draft): string
    {
        $answers = $draft->answers ?? [];
        $user = $draft->user->load('profile');
        $visa = $draft->visaType->load('country');
        
        $apiKey = config('services.openai.key');
        
        if (!$apiKey) {
            return $this->generateFallback($draft);
        }

        $client = \OpenAI::client($apiKey);

        $prompt = $this->buildPrompt($user, $visa, $answers);

        try {
            $response = $client->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a professional immigration consultant and academic advisor. Your goal is to write a high-quality, formal, and persuasive Statement of Purpose (SOP) that meets international visa standards.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("SOP Generation Error: " . $e->getMessage());
            return $this->generateFallback($draft);
        }
    }

    private function buildPrompt($user, $visa, $answers): string
    {
        $profile = $user->profile;
        $countryName = $visa->country?->name ?? 'your destination';
        $visaName = $visa->name ?? 'the visa program';

        return "Write a professional Statement of Purpose for an applicant named {$user->name}. 
        
Applicant Profile:
- Name: {$user->name}
- Education: {$profile->education_level}
- Work Experience: {$profile->work_experience_years} years
- Target Country: {$countryName}
- Target Program/Visa: {$visaName}

Specific details provided by the applicant:
1. Academic Background & Achievements: " . ($answers['education_background'] ?? '') . ". " . ($answers['education_details'] ?? '') . "
2. Career Ambitions & Long-term Goals: " . ($answers['career_goals'] ?? '') . ". " . ($answers['career_relevance'] ?? '') . "
3. Motivation for {$countryName}: " . ($answers['why_country'] ?? '') . "
4. Specific Interest in the Institution/Route: " . ($answers['why_school'] ?? '') . "

The SOP should be:
- Approximately 800-1000 words.
- Written in a formal, academic, yet personal tone.
- Structured with an impactful introduction, detailed middle sections connecting past experience to future goals, and a strong conclusion.
- Free of generic cliches, focusing instead on the specific logic connecting the applicant's history to this specific {$visaName}.";
    }

    private function generateFallback(SopDraft $draft): string
    {
        $answers = $draft->answers ?? [];
        $user = $draft->user;
        $visa = $draft->visaType;

        $sections = [];
        $sections[] = "Statement of Purpose: {$user->name}";
        $sections[] = "My name is {$user->name}, and I am writing to express my strong interest in " . ($visa->name ?? 'my studies') . " in " . ($visa->country?->name ?? 'your destination') . ".";
        
        if (!empty($answers['education_background'])) {
            $sections[] = "Academic Foundation: I have a background in {$answers['education_background']}. " . ($answers['education_details'] ?? '');
        }

        if (!empty($answers['career_goals'])) {
            $sections[] = "Professional Aspirations: My goal is to {$answers['career_goals']}. " . ($answers['career_relevance'] ?? '');
        }

        $sections[] = "Conclusion: I believe my background aligns with {$visa->name}. thank you.";

        return implode("\n\n", $sections);
    }
}
