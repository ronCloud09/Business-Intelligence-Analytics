<?php

namespace App\Jobs;

use App\Services\AI\AIManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Queued wrapper around AIManager::generateFullReport(). This produces
 * the Executive Summary, Top Recommendations, Risk Analysis, Business
 * Health, and Department Insights bundle in ONE Gemini request.
 *
 * Used by the scheduler (Package 6, every 12 hours) and by the manual
 * refresh button — both just dispatch this job instead of calling
 * AIManager synchronously, so a slow AI response never blocks an HTTP
 * request.
 *
 * Note: this is intentionally the only job that generates the
 * recommendations/risk/summary bundle. Separate
 * GenerateRecommendations/GenerateRiskInsight jobs were in the original
 * file list, but creating them as independent Gemini-calling jobs would
 * contradict the "one request for the whole bundle" rule elsewhere in
 * the spec — so recommendations and risk analysis are produced here,
 * not in their own jobs. Per-department event-driven jobs (Package 7)
 * are a different, legitimate case: those really do need separate small
 * requests, one per triggering event.
 */
class GenerateDashboardInsight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $timeout = 120;

    public function __construct(
        protected string $triggeredBy = 'scheduler',
        protected ?string $reason = null,
    ) {}

    public function handle(AIManager $aiManager): void
    {
        $aiManager->generateFullReport($this->triggeredBy, $this->reason);
    }
}
