<?php

namespace App\Console\Commands;

use App\Models\InventoryDeptCategory;
use App\Models\InventoryDeptItem;
use App\Models\InventoryDeptStockLevel;
use App\Models\InventoryDeptWarehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncInventoryData extends Command
{
    protected $signature = 'sync:inventory';

    protected $description = 'Pull categories, warehouses, items, and stock levels from the Inventory department database';

    public function handle(): int
    {
        $this->info('Syncing inventory_dept -> local tables...');

        $categories = DB::connection('inventory_dept')->table('categories')->get();
        foreach ($categories as $row) {
            InventoryDeptCategory::updateOrCreate(
                ['source_id' => $row->id],
                ['name' => $row->name]
            );
        }
        $this->info("Synced {$categories->count()} categories.");

        $warehouses = DB::connection('inventory_dept')->table('warehouses')->get();
        foreach ($warehouses as $row) {
            InventoryDeptWarehouse::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'name' => $row->name,
                    'province' => $row->province,
                    'city' => $row->city,
                    'barangay' => $row->barangay,
                    'address_description' => $row->address_description,
                    'country' => $row->country,
                    'capacity_units' => $row->capacity_units,
                    'status' => $row->status,
                ]
            );
        }
        $this->info("Synced {$warehouses->count()} warehouses.");

        $items = DB::connection('inventory_dept')->table('items')->get();
        foreach ($items as $row) {
            InventoryDeptItem::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'sku' => $row->sku,
                    'name' => $row->name,
                    'source_category_id' => $row->category_id,
                    'unit_cost' => $row->unit_cost,
                ]
            );
        }
        $this->info("Synced {$items->count()} items.");

        $stockLevels = DB::connection('inventory_dept')->table('stock_levels')->get();
        foreach ($stockLevels as $row) {
            InventoryDeptStockLevel::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'source_item_id' => $row->item_id,
                    'source_warehouse_id' => $row->warehouse_id,
                    'quantity_on_hand' => $row->quantity_on_hand,
                    'quantity_reserved' => $row->quantity_reserved,
                    'reorder_threshold' => $row->reorder_threshold,
                ]
            );
        }
        $this->info("Synced {$stockLevels->count()} stock levels.");

        $this->info('Inventory sync complete.');

        return self::SUCCESS;
    }
}
