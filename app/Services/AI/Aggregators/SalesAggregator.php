<?php

namespace App\Services\AI\Aggregators;

use App\Services\Departments\SalesService;

/**
 * Shapes Sales / E-Commerce KPIs for AI consumption.
 */
class SalesAggregator
{
    public function __construct(protected SalesService $salesService) {}

    /**
     * @return array<string, mixed>
     */
    public function summarize(): array
    {
        return $this->salesService->getKpiSummaryForAi();
    }
}
