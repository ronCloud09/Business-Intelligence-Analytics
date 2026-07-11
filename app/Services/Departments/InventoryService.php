<?php

namespace App\Services\Departments;

use App\Models\InventoryItem;
use Illuminate\Support\Facades\DB;

/**
 * Computes Inventory & Warehouse KPIs from inventory_items.
 */
class InventoryService
{
    /**
     * @return array<string, mixed>
     */
    public function getSnapshot(): array
    {
        return [
            'total_skus' => $this->totalSkus(),
            'low_stock_count' => $this->lowStockCount(),
            'out_of_stock_count' => $this->outOfStockCount(),
            'inventory_value' => $this->totalInventoryValue(),
            'low_stock_items' => $this->lowStockItems(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getKpiSummaryForAi(): array
    {
        return [
            'total_skus' => $this->totalSkus(),
            'low_stock_count' => $this->lowStockCount(),
            'out_of_stock_count' => $this->outOfStockCount(),
            'inventory_value' => $this->totalInventoryValue(),
            'critical_items' => $this->lowStockItems()->take(5)->values()->toArray(),
        ];
    }

    public function totalSkus(): int
    {
        return InventoryItem::count();
    }

    public function lowStockCount(): int
    {
        return InventoryItem::lowStock()->count();
    }

    public function outOfStockCount(): int
    {
        return InventoryItem::outOfStock()->count();
    }

    public function totalInventoryValue(): float
    {
        return (float) InventoryItem::query()
            ->select(DB::raw('SUM(quantity_on_hand * unit_cost) as total'))
            ->value('total') ?? 0.0;
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function lowStockItems()
    {
        return InventoryItem::lowStock()
            ->orderBy('quantity_on_hand')
            ->get()
            ->map(fn (InventoryItem $item) => [
                'sku' => $item->sku,
                'name' => $item->name,
                'quantity_on_hand' => $item->quantity_on_hand,
                'reorder_threshold' => $item->reorder_threshold,
            ]);
    }

    /**
     * Checks whether a single item has crossed below its reorder threshold.
     * Used by the event-driven insight trigger in Package 7.
     */
    public function hasBreachedThreshold(InventoryItem $item): bool
    {
        return $item->isLowStock() || $item->isOutOfStock();
    }
}
