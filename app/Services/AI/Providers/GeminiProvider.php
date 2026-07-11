<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiProvider implements AIProviderInterface
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = (string) config('ai.providers.gemini.api_key');
        $this->model = (string) config('ai.providers.gemini.model');
        $this->baseUrl = rtrim((string) config('ai.providers.gemini.base_url'), '/');
        $this->timeout = (int) config('ai.providers.gemini.timeout', 60);
    }

    public function generate(string $systemPrompt, string $userPrompt, bool $jsonMode = false): array
    {
        try {

            if (empty($this->apiKey)) {
                throw new RuntimeException('GEMINI_API_KEY is not set. Add it to your .env file.');
            }

            $url = "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}";

            $generationConfig = [
                'temperature' => 0.4,
            ];

            if ($jsonMode) {
                $generationConfig['responseMimeType'] = 'application/json';
            }

            $response = Http::retry(3, 500)
                ->timeout($this->timeout)
                ->acceptJson()
                ->post($url, [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => $systemPrompt],
                        ],
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $userPrompt],
                            ],
                        ],
                    ],
                    'generationConfig' => $generationConfig,
                ]);

            if ($response->failed()) {
                throw new RuntimeException(
                    'Gemini API request failed ('.$response->status().'): '.$response->body()
                );
            }

            $data = $response->json();

            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($content === null) {
                throw new RuntimeException(
                    'Gemini API returned no content: '.json_encode($data)
                );
            }

            return [
                'content' => $content,
                'input_tokens' => (int) ($data['usageMetadata']['promptTokenCount'] ?? 0),
                'output_tokens' => (int) ($data['usageMetadata']['candidatesTokenCount'] ?? 0),
            ];

        } catch (\Throwable $e) {
            \Log::error('Gemini Error: '.$e->getMessage());
            \Log::error($e->getTraceAsString());

            throw $e;
        }
    }
}