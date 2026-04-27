<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * HTTP client để giao tiếp với Python FastAPI service.
 * Phương án A: Laravel gọi Python REST API.
 */
class PythonAiService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.python_ai.url', 'http://localhost:8001'), '/');
        $this->timeout = (int) config('services.python_ai.timeout', 60);
    }

    /**
     * Gọi Python AI để tạo meal plan 7 ngày.
     *
     * @param array $payload  {user_id, goal_type, target_calories, protein_grams,
     *                         carbs_grams, fat_grams, allergens, disliked_foods, foods}
     * @return array          {generation_method, days: [{day_number, meals: [...]}]}
     * @throws \RuntimeException nếu Python không phản hồi hoặc lỗi
     */
    public function generateMealPlan(array $payload): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/ai/generate-meal-plan", $payload);

            if ($response->failed()) {
                $error = $response->json('detail') ?? $response->body();
                if (is_array($error)) {
                    $error = json_encode($error, JSON_UNESCAPED_UNICODE);
                }
                Log::error('PythonAiService::generateMealPlan failed', [
                    'status' => $response->status(),
                    'error'  => $error,
                ]);
                throw new \RuntimeException("Python AI service error: {$error}");
            }

            return $response->json();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('PythonAiService: cannot connect to Python', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Không thể kết nối đến AI service. Vui lòng thử lại sau.');
        }
    }

    /**
     * Ping health-check Python service.
     */
    public function ping(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }
}
