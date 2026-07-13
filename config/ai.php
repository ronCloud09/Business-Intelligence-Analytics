<?php

use App\Services\AI\Providers\GeminiProvider;

return [

    'default' => env('AI_PROVIDER', 'gemini'),

    'providers' => [

        'gemini' => [
            'driver' => GeminiProvider::class,
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-3.5-flash'),
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
            'timeout' => (int) env('GEMINI_TIMEOUT', 60),
        ],
    ],
    'generation_interval_hours' => (int) env('AI_GENERATION_INTERVAL_HOURS', 12),
    'cache_ttl_hours' => (int) env('AI_CACHE_TTL_HOURS', 12),

    'chatbot' => [
        'name' => 'Nexora AI',
        'db_lookup_keywords' => ['today', 'current', 'right now', 'how many', 'how much'],
    ],
    'thresholds' => [
        'inventory_low_stock_enabled' => true,
        'manufacturing_downtime_enabled' => true,
        'finance_anomaly_enabled' => true,
    ],
];
