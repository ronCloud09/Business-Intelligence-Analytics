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
     * Get the complete live inventory snapshot.
     *
     * @return array<string, mixed>
     */
    public function getSnapshot(): array
    {
        return [
            'total_skus' => $this->totalSkus(),
            'low_stock_count' => $this->lowStockCount(),
            'out_of_stock_count' => $this->outOfStockCount(),
            'inventory_value' => $this->totalInventoryValue(),

            // Give the AI the actual item details.
            'low_stock_items' => $this->lowStockItems()
                ->values()
                ->toArray(),

            'out_of_stock_items' => $this->outOfStockItems()
                ->values()
                ->toArray(),
        ];
    }

    /**
     * Inventory KPI summary used during AI report generation.
     *
     * @return array<string, mixed>
     */
    public function getKpiSummaryForAi(): array
    {
        return [
            'total_skus' => $this->totalSkus(),
            'low_stock_count' => $this->lowStockCount(),
            'out_of_stock_count' => $this->outOfStockCount(),
            'inventory_value' => $this->totalInventoryValue(),

            'critical_items' => $this->lowStockItems()
                ->take(5)
                ->values()
                ->toArray(),

            'out_of_stock_items' => $this->outOfStockItems()
                ->take(5)
                ->values()
                ->toArray(),
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
        return (float) (
            InventoryItem::query()
                ->select(
                    DB::raw(
                        'SUM(quantity_on_hand * unit_cost) as total'
                    )
                )
                ->value('total') ?? 0.0
        );
    }

    /**
     * Get all low-stock items.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function lowStockItems()
    {
        return InventoryItem::lowStock()
            ->orderBy('quantity_on_hand')
            ->get()
            ->map(fn(InventoryItem $item) => [
                'sku' => $item->sku,
                'name' => $item->name,
                'category' => $item->category,
                'warehouse_zone' => $item->warehouse_zone,
                'quantity_on_hand' => $item->quantity_on_hand,
                'reorder_threshold' => $item->reorder_threshold,
                'unit_cost' => (float) $item->unit_cost,
                'stock_shortage' => max(
                    0,
                    $item->reorder_threshold
                    - $item->quantity_on_hand
                ),
            ]);
    }

    /**
     * Get all completely out-of-stock items.
     *
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    public function outOfStockItems()
    {
        return InventoryItem::outOfStock()
            ->orderBy('name')
            ->get()
            ->map(fn(InventoryItem $item) => [
                'sku' => $item->sku,
                'name' => $item->name,
                'category' => $item->category,
                'warehouse_zone' => $item->warehouse_zone,
                'quantity_on_hand' => $item->quantity_on_hand,
                'reorder_threshold' => $item->reorder_threshold,
                'unit_cost' => (float) $item->unit_cost,
                'stock_shortage' => max(
                    0,
                    $item->reorder_threshold
                    - $item->quantity_on_hand
                ),
            ]);
    }

    /**
     * Check whether an item breached its reorder threshold.
     */
    public function hasBreachedThreshold(
        InventoryItem $item
    ): bool {
        return $item->isLowStock()
            || $item->isOutOfStock();
    }
}