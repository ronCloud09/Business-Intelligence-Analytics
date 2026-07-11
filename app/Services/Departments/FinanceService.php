<?php

namespace App\Services\Departments;

use App\Models\FinanceTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Computes Finance & Accounting KPIs from finance_transactions.
 *
 * This is the single source of truth for finance numbers: both the
 * Blade dashboard and the AI Finance Aggregator (Package 2) call into
 * this service instead of querying the database directly.
 */
class FinanceService
{
    /**
     * Full KPI snapshot for the current period, used by the dashboard
     * and department analytics views.
     *
     * @return array<string, mixed>
     */
    public function getSnapshot(): array
    {
        return [
            'revenue' => $this->totalRevenue(),
            'expenses' => $this->totalExpenses(),
            'profit_margin' => $this->profitMarginPercent(),
            'overdue_payments' => $this->overduePaymentsTotal(),
            'overdue_count' => $this->overduePaymentsCount(),
            'revenue_by_category' => $this->revenueByCategory(),
        ];
    }

    /**
     * A compact KPI array intended for AI summarization (Package 2).
     * Kept separate from getSnapshot() so the AI-facing shape can evolve
     * independently of what the dashboard needs to render.
     *
     * @return array<string, mixed>
     */
    public function getKpiSummaryForAi(): array
    {
        return [
            'revenue' => $this->totalRevenue(),
            'profit_margin_percent' => $this->profitMarginPercent(),
            'expenses' => $this->totalExpenses(),
            'overdue_payments_total' => $this->overduePaymentsTotal(),
            'overdue_payments_count' => $this->overduePaymentsCount(),
        ];
    }

    public function totalRevenue(): float
    {
        return (float) FinanceTransaction::revenue()->sum('amount');
    }

    public function totalExpenses(): float
    {
        return (float) FinanceTransaction::expense()->sum('amount');
    }

    public function profitMarginPercent(): float
    {
        $revenue = $this->totalRevenue();

        if ($revenue <= 0) {
            return 0.0;
        }

        return round((($revenue - $this->totalExpenses()) / $revenue) * 100, 2);
    }

    public function overduePaymentsTotal(): float
    {
        return (float) FinanceTransaction::overdue()->sum('amount');
    }

    public function overduePaymentsCount(): int
    {
        return FinanceTransaction::overdue()->count();
    }

    /**
     * @return array<int, array{category: string, total: float}>
     */
    public function revenueByCategory(): array
    {
        return FinanceTransaction::revenue()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'category' => $row->category,
                'total' => (float) $row->total,
            ])
            ->toArray();
    }

    /**
     * Revenue trend for the last N days, used by the dashboard chart.
     *
     * @return array<int, array{date: string, total: float}>
     */
    public function revenueTrend(int $days = 7): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $rows = FinanceTransaction::revenue()
            ->where('transaction_date', '>=', $start)
            ->select('transaction_date', DB::raw('SUM(amount) as total'))
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->transaction_date)->toDateString());

        $trend = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $trend[] = [
                'date' => $date,
                'total' => isset($rows[$date]) ? (float) $rows[$date]->total : 0.0,
            ];
        }

        return $trend;
    }
}
