<?php

namespace App\Services\Departments;

use App\Models\InventoryItem;
use App\Models\SalesOrder;
use Illuminate\Support\Carbon;

/**
 * Computes Sales / E-Commerce KPIs from sales_orders.
 */
class SalesService
{
    /**
     * @return array<string, mixed>
     */
    public function getSnapshot(): array
    {
        return [
            'total_revenue' => $this->totalRevenue(),
            'total_orders' => $this->totalOrders(),
            'new_customers' => $this->newCustomersCount(),
            'conversion_rate_percent' => $this->conversionRatePercent(),
            'top_products' => $this->topProducts(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getKpiSummaryForAi(): array
    {
        return [
            'total_revenue' => $this->totalRevenue(),
            'total_orders' => $this->totalOrders(),
            'new_customers' => $this->newCustomersCount(),
            'top_products' => $this->topProducts(3),
        ];
    }

    public function totalRevenue(): float
    {
        return (float) SalesOrder::sum('revenue');
    }

    public function totalOrders(): int
    {
        return (int) SalesOrder::sum('units_sold');
    }

    public function newCustomersCount(): int
    {
        return SalesOrder::where('is_new_customer', true)->count();
    }

    /**
     * Percentage of all sales orders that came from new customers, used
     * as a simple stand-in for a true funnel conversion rate.
     */
    public function conversionRatePercent(): float
    {
        $total = SalesOrder::count();

        if ($total === 0) {
            return 0.0;
        }

        return round(($this->newCustomersCount() / $total) * 100, 2);
    }

    /**
     * @return array<int, array{product_name: string, units_sold: int, revenue: float}>
     */
    public function topProducts(int $limit = 10): array
    {
        return SalesOrder::query()
            ->selectRaw('product_name, SUM(units_sold) as units_sold, SUM(revenue) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('units_sold')
            ->limit($limit)
            ->get()
            ->map(fn($row) => [
                'product_name' => $row->product_name,
                'units_sold' => (int) $row->units_sold,
                'revenue' => (float) $row->revenue,
            ])
            ->toArray();
    }

    /**
     * @return array<int, array{date: string, total: float}>
     */
    public function revenueTrend(int $days = 7): array
    {
        $start = Carbon::today()->subDays($days - 1);

        $rows = SalesOrder::where('order_date', '>=', $start)
            ->selectRaw('order_date, SUM(revenue) as total')
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->get()
            ->keyBy(fn($row) => Carbon::parse($row->order_date)->toDateString());

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

    /**
     * Top products with period-over-period comparison and inventory
     * health, for the dashboard's "Products Driving Growth" table.
     * Matches sales orders to inventory items by product name
     * (best-effort — items with no inventory match show as 'Unknown').
     *
     * @return array<int, array{name: string, units_sold: int, prev_units: int, revenue: float, coverage: int, stock_status: string, stock_class: string}>
     */
    public function topProductsDetailed(int $limit = 10): array
    {
        $now = Carbon::now();
        $currentStart = $now->copy()->subDays(30);
        $previousStart = $now->copy()->subDays(60);

        $current = SalesOrder::where('order_date', '>=', $currentStart)
            ->selectRaw('product_name, SUM(units_sold) as units_sold, SUM(revenue) as revenue')
            ->groupBy('product_name')
            ->orderByDesc('units_sold')
            ->limit($limit)
            ->get();

        $previous = SalesOrder::whereBetween('order_date', [$previousStart, $currentStart])
            ->selectRaw('product_name, SUM(units_sold) as units_sold')
            ->groupBy('product_name')
            ->pluck('units_sold', 'product_name');

        $inventoryByName = InventoryItem::all()->keyBy('name');

        return $current->map(function ($row) use ($previous, $inventoryByName) {
            $prevUnits = (int) ($previous[$row->product_name] ?? 0);
            $item = $inventoryByName->get($row->product_name);

            if (!$item) {
                $coverage = 0;
                $stockStatus = 'Unknown';
                $stockClass = 'bg-med';
            } elseif ($item->quantity_on_hand <= $item->reorder_threshold) {
                $coverage = $item->reorder_threshold > 0
                    ? min(100, (int) round(($item->quantity_on_hand / $item->reorder_threshold) * 100))
                    : 0;
                $stockStatus = 'Low Stock';
                $stockClass = 'bg-high';
            } else {
                $coverage = $item->reorder_threshold > 0
                    ? min(100, (int) round(($item->quantity_on_hand / ($item->reorder_threshold * 2)) * 100))
                    : 100;
                $stockStatus = $coverage < 60 ? 'Adequate' : 'In Stock';
                $stockClass = $coverage < 60 ? 'bg-med' : 'bg-low';
            }

            return [
                'name' => $row->product_name,
                'units_sold' => (int) $row->units_sold,
                'prev_units' => $prevUnits,
                'revenue' => (float) $row->revenue,
                'coverage' => $coverage,
                'stock_status' => $stockStatus,
                'stock_class' => $stockClass,
            ];
        })->values()->toArray();
    }
}
