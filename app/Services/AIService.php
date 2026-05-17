<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class AIService
{
    /**
     * Generate AI summary and priority for a given task.
     *
     * @param Task $task
     * @return array
     */
    public function generateSummary(Task $task): array
    {
        $apiKey = env('OPENAI_API_KEY');
        $model = env('OPENAI_MODEL', 'gpt-3.5-turbo');

        // Mock fallback immediately if API key is not set
        if (empty($apiKey)) {
            Log::warning("OPENAI_API_KEY is not set. Using mock fallback for task #{$task->id}.");
            return $this->getMockFallback($task);
        }

        // Construct the strict JSON prompt
        $prompt = $this->buildPrompt($task);

        try {
            // Call OpenAI API using Laravel HTTP client with a reasonable timeout
            $response = Http::withToken($apiKey)
                ->timeout(10) // 10 seconds timeout
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a professional task analyzer. Always respond with raw JSON.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->failed()) {
                Log::error("OpenAI API request failed for task #{$task->id}: " . $response->body());
                return $this->getMockFallback($task);
            }

            // Parse response
            $content = $response->json('choices.0.message.content');
            
            if (!$content) {
                Log::error("OpenAI API response format invalid for task #{$task->id}");
                return $this->getMockFallback($task);
            }

            // Clean markdown JSON block formatting if API includes it
            $content = str_replace(['```json', '```'], '', $content);
            $parsed = json_decode(trim($content), true);

            // Validate parsed JSON
            if (json_last_error() !== JSON_ERROR_NONE || !isset($parsed['summary']) || !isset($parsed['priority'])) {
                Log::error("Failed to parse JSON from OpenAI for task #{$task->id}: " . $content);
                return $this->getMockFallback($task);
            }

            // Map response to expected database column names
            return [
                'ai_summary' => $parsed['summary'],
                'ai_priority' => strtolower($parsed['priority']),
            ];

        } catch (\Exception $e) {
            // Gracefully catch exceptions (e.g., ConnectionTimeout)
            Log::error("Exception occurred while calling AI for task #{$task->id}: " . $e->getMessage());
            return $this->getMockFallback($task);
        }
    }

    /**
     * Build the prompt used for the OpenAI API request.
     */
    private function buildPrompt(Task $task): string
    {
        $dueDate = $task->due_date ? $task->due_date->format('Y-m-d') : 'None';
        
        return <<<EOT
Analyze the following task and generate:
1. A short professional summary
2. Suggested priority level (low, medium, high)

Return response in JSON format only.

Use this JSON response format:
{
    "summary": "Task summary",
    "priority": "high"
}

Task Details:
Title: {$task->title}
Description: {$task->description}
Current Priority: {$task->priority->value}
Due Date: {$dueDate}
EOT;
    }

    /**
     * Return a mock fallback response to prevent application crashing.
     */
    private function getMockFallback(Task $task): array
    {
        $priorities = ['low', 'medium', 'high'];
        $mockedPriority = $priorities[array_rand($priorities)];

        return [
            'ai_summary' => "AI Summary (Fallback): This task involves \"{$task->title}\". Based on the context, it requires attention to meet its objectives.",
            'ai_priority' => $mockedPriority,
        ];
    }
}
