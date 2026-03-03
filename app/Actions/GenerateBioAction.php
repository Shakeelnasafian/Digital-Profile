<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Profile;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class GenerateBioAction
{
    public function __invoke(Profile $profile, string $context): string
    {
        $apiKey = config('services.groq.api_key');

        if (! $apiKey) {
            throw ValidationException::withMessages([
                'context' => 'AI bio generation is not configured. Please add a GROQ_API_KEY.',
            ]);
        }

        $systemPrompt = 'You are a professional bio writer. Write a concise 2-3 sentence professional bio. '
            . 'Be direct, confident, and avoid clichés. Return only the bio text, no labels, no commentary, no quotes.';

        $userPrompt = implode('. ', array_filter([
            $profile->display_name ? 'Name: ' . $profile->display_name : null,
            $profile->job_title    ? 'Title: ' . $profile->job_title    : null,
            'Context: ' . $context,
        ]));

        $response = Http::withToken($apiKey)
            ->timeout(30)
            ->post(config('services.groq.base_url') . '/chat/completions', [
                'model'       => 'llama-3.3-70b-versatile',
                'messages'    => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $userPrompt],
                ],
                'max_tokens'  => 200,
                'temperature' => 0.7,
            ]);

        if (! $response->successful()) {
            throw ValidationException::withMessages([
                'context' => 'AI service returned an error. Please try again.',
            ]);
        }

        $bio = $response->json('choices.0.message.content');

        if (! $bio || ! is_string($bio)) {
            throw ValidationException::withMessages([
                'context' => 'AI service returned an unexpected response. Please try again.',
            ]);
        }

        return trim($bio);
    }
}
