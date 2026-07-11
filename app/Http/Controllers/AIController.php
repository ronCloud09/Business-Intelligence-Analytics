<?php

namespace App\Http\Controllers;

use App\Models\AIGeneration;
use App\Services\AI\AIManager;
use Illuminate\Http\JsonResponse;

class AIController extends Controller
{
    public function __construct(protected AIManager $aiManager) {}

    /**
     * Return the current AI generation and its report sections as JSON.
     * Used for testing Packages 1-2 before the dashboard/chatbot views
     * are wired up in Package 5.
     */
    public function current(): JsonResponse
    {
        $generation = AIGeneration::with('reports')
            ->where('is_current', true)
            ->first();

        if (! $generation) {
            return response()->json([
                'message' => 'No AI report has been generated yet. POST /nexora-ai/refresh to generate one.',
            ], 404);
        }

        return response()->json([
            'generation' => $generation,
            'reports' => $generation->reports,
        ]);
    }

    /**
     * Manually trigger a full report generation. This is the same action
     * the "Manual Refresh" button (Package 6) will call.
     */
    public function refresh(): JsonResponse
    {
        try {
            $generation = $this->aiManager->generateFullReport('manual', 'manual_refresh_endpoint');

            return response()->json([
                'message' => 'Report generated successfully.',
                'generation' => $generation,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Report generation failed: '.$e->getMessage(),
            ], 500);
        }
    }
}
