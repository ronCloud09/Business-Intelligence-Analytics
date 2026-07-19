<?php

namespace App\Services\Departments;

use App\Models\FulfillmentOrder;
use App\Models\FulfillmentPackingMaterial;
use App\Models\FulfillmentShipment;

/**
 * Computes Order Fulfillment KPIs from data synced from the Order
 * Fulfillment department's database (via `sync:fulfillment`).
 *
 * As of the initial sync, their system has orders but no shipment
 * history yet — so fulfillment rate / delayed shipments genuinely
 * can't be computed. Methods return null in that case rather than a
 * fabricated number; the caller decides how to render "no data yet".
 */
class FulfillmentService
{
    public function pendingOrdersCount(): int
    {
        return FulfillmentOrder::where('status', 'NEW')->count();
    }

    public function totalOrdersCount(): int
    {
        return FulfillmentOrder::count();
    }

    public function totalShipmentsCount(): int
    {
        return FulfillmentShipment::count();
    }

    /**
     * Percentage of shipments delivered on or before their due date.
     * Returns null if there's no shipment data yet to compute from.
     */
    public function fulfillmentRatePercent(): ?float
    {
        $total = FulfillmentShipment::count();

        if ($total === 0) {
            return null;
        }

        $onTime = FulfillmentShipment::whereColumn('source_updated_at', '<=', 'due_date')->count();

        return round(($onTime / $total) * 100, 2);
    }

    /**
     * Count of shipments still not delivered past their due date.
     * Returns null if there's no shipment data yet.
     */
    public function delayedShipmentsCount(): ?int
    {
        if (FulfillmentShipment::count() === 0) {
            return null;
        }

        return FulfillmentShipment::where('due_date', '<', now())
            ->whereNotIn('status', ['Delivered', 'Completed'])
            ->count();
    }

    public function lowStockPackingMaterialsCount(): int
    {
        return FulfillmentPackingMaterial::lowStock()->count();
    }

    /**
     * @return array<int, array{name: string, stock_qty: int, low_stock_threshold: int}>
     */
    public function lowStockPackingMaterials(): array
    {
        return FulfillmentPackingMaterial::lowStock()
            ->orderBy('stock_qty')
            ->get()
            ->map(fn (FulfillmentPackingMaterial $item) => [
                'name' => $item->name,
                'stock_qty' => $item->stock_qty,
                'low_stock_threshold' => $item->low_stock_threshold,
            ])
            ->toArray();
    }
}
