<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenRouterChatService
{
    /**
     * Send chat messages to OpenRouter and return a normalized response.
     *
     * @throws RuntimeException
     * @throws RequestException
     */
    public function chat(array $messages, array $options = []): array
    {
        $apiKey = config('services.openrouter.api_key');

        if (blank($apiKey)) {
            throw new RuntimeException('OPENROUTER_API_KEY belum diatur.');
        }

        $baseUrl = rtrim((string) config('services.openrouter.base_url'), '/');
        $payload = [
            'model' => $options['model'] ?? config('services.openrouter.model'),
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? 0.7,
            'max_tokens' => $options['max_tokens'] ?? 500,
        ];

        $response = Http::acceptJson()
            ->asJson()
            ->timeout(60)
            ->retry(2, 500)
            ->withToken($apiKey)
            ->withHeaders([
                'HTTP-Referer' => (string) config('services.openrouter.site_url'),
                'X-Title' => (string) config('services.openrouter.app_name'),
            ])
            ->post($baseUrl.'/chat/completions', $payload)
            ->throw();

        $data = $response->json();
        $content = data_get($data, 'choices.0.message.content');

        return [
            'id' => data_get($data, 'id'),
            'model' => data_get($data, 'model', $payload['model']),
            'content' => is_string($content) ? trim($content) : $this->normalizeContent($content),
            'usage' => data_get($data, 'usage', []),
            'raw' => $data,
        ];
    }

    private function normalizeContent(mixed $content): string
    {
        if (is_string($content)) {
            return trim($content);
        }

        if (!is_array($content)) {
            return '';
        }

        $parts = collect($content)
            ->map(function ($item) {
                if (is_string($item)) {
                    return $item;
                }

                if (is_array($item) && ($item['type'] ?? null) === 'text') {
                    return $item['text'] ?? '';
                }

                return '';
            })
            ->filter()
            ->implode("\n");

        return trim($parts);
    }
}
