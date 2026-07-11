<?php

namespace App\Services\Departments;

use App\Models\ManufacturingMachine;
use App\Models\ManufacturingProductionLog;
use Illuminate\Support\Carbon;

/**
 * Computes Manufacturing & Production KPIs from manufacturing_machines
 * and manufacturing_production_logs.
 */
class ManufacturingService
{
    /**
     * @return array<string, mixed>
     */
    public function getSnapshot(): array
    {
        return [
            'total_downtime_minutes' => $this->totalDowntimeMinutesToday(),
            'production_rate_percent' => $this->productionRatePercent(),
            'machines_down' => $this->machinesDownCount(),
            'machine_status' => $this->machineStatusBreakdown(),
            'defect_rate_percent' => $this->defectRatePercent(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getKpiSummaryForAi(): array
    {
        return [
            'total_downtime_minutes' => $this->totalDowntimeMinutesToday(),
            'production_rate_percent' => $this->productionRatePercent(),
            'machines_down' => $this->machinesDownCount(),
            'defect_rate_percent' => $this->defectRatePercent(),
        ];
    }

    public function totalDowntimeMinutesToday(): int
    {
        return (int) ManufacturingMachine::sum('downtime_minutes_today');
    }

    public function machinesDownCount(): int
    {
        return ManufacturingMachine::down()->count();
    }

    /**
     * @return array<string, int>
     */
    public function machineStatusBreakdown(): array
    {
        return ManufacturingMachine::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    public function productionRatePercent(): float
    {
        $today = Carbon::today();

        $totals = ManufacturingProductionLog::query()
            ->where('log_date', $today)
            ->selectRaw('SUM(units_produced) as produced, SUM(units_target) as target')
            ->first();

        if (! $totals || (int) $totals->target === 0) {
            return 0.0;
        }

        return round(($totals->produced / $totals->target) * 100, 2);
    }

    public function defectRatePercent(): float
    {
        $today = Carbon::today();

        $totals = ManufacturingProductionLog::query()
            ->where('log_date', $today)
            ->selectRaw('SUM(defect_count) as defects, SUM(units_produced) as produced')
            ->first();

        if (! $totals || (int) $totals->produced === 0) {
            return 0.0;
        }

        return round(($totals->defects / $totals->produced) * 100, 2);
    }

    /**
     * Used by the event-driven insight trigger in Package 7.
     */
    public function isMachineDown(ManufacturingMachine $machine): bool
    {
        return $machine->status === 'down';
    }
}
