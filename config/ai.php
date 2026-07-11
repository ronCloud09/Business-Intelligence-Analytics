<?php

use App\Services\AI\Providers\GeminiProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | Which provider AIRouter resolves. To switch from Gemini to OpenAI,
    | change AI_PROVIDER in .env to "openai" (once an OpenAIProvider is
    | registered below) — no other file needs to change.
    |
    */

    'default' => env('AI_PROVIDER', 'gemini'),

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | Each provider must implement App\Services\AI\Contracts\AIProviderInterface.
    | AIRouter resolves the "driver" class out of the container.
    |
    */

    'providers' => [

        'gemini' => [
            'driver' => GeminiProvider::class,
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
            'timeout' => (int) env('GEMINI_TIMEOUT', 60),
        ],

        // Example shape for adding OpenAI later (Package 3+ note, not built yet):
        // 'openai' => [
        //     'driver' => \App\Services\AI\Providers\OpenAIProvider::class,
        //     'api_key' => env('OPENAI_API_KEY'),
        //     'model' => env('OPENAI_MODEL', 'gpt-4o'),
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Report Generation
    |--------------------------------------------------------------------------
    */

    // How often the scheduler (Package 6) regenerates the Executive
    // Summary / Recommendations / Risk Analysis bundle.
    'generation_interval_hours' => (int) env('AI_GENERATION_INTERVAL_HOURS', 12),

    // How long the dashboard/chatbot may serve a cached report before
    // treating it as stale (used by CacheService in Package 3).
    'cache_ttl_hours' => (int) env('AI_CACHE_TTL_HOURS', 12),

    /*
    |--------------------------------------------------------------------------
    | Chatbot
    |--------------------------------------------------------------------------
    */

    'chatbot' => [
        'name' => 'Nexora AI',
        // Question patterns that should be answered from the database
        // directly, with no Gemini call. Extended in Package 3's ChatService.
        'db_lookup_keywords' => ['today', 'current', 'right now', 'how many', 'how much'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Event-Driven Triggers (Package 7)
    |--------------------------------------------------------------------------
    */

    'thresholds' => [
        'inventory_low_stock_enabled' => true,
        'manufacturing_downtime_enabled' => true,
        'finance_anomaly_enabled' => true,
    ],
];
