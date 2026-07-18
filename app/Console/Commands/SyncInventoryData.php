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

        // Categories
        $categories = DB::connection('inventory_dept')->table('categories')->get();
        foreach ($categories as $row) {
            \App\Models\InventoryDeptCategory::updateOrCreate(
                ['source_id' => $row->id],
                ['name' => $row->name, 'source_created_at' => $row->created_at ?? now(), 'source_updated_at' => $row->updated_at ?? now()]
            );
        }
        $this->info("Synced {$categories->count()} categories.");

        // Warehouses
        $warehouses = DB::connection('inventory_dept')->table('warehouses')->get();
        foreach ($warehouses as $row) {
            \App\Models\InventoryDeptWarehouse::updateOrCreate(
                ['source_id' => $row->id],
                ['name' => $row->name, 'location' => $row->location ?? null, 'source_created_at' => $row->created_at ?? now(), 'source_updated_at' => $row->updated_at ?? now()]
            );
        }
        $this->info("Synced {$warehouses->count()} warehouses.");

        // Items
        $items = DB::connection('inventory_dept')->table('items')->get();
        foreach ($items as $row) {
            \App\Models\InventoryDeptItem::updateOrCreate(
                ['source_id' => $row->id],
                ['sku' => $row->sku ?? '', 'name' => $row->name, 'source_category_id' => $row->category_id ?? null, 'unit_cost' => $row->unit_cost ?? 0, 'source_created_at' => $row->created_at ?? now(), 'source_updated_at' => $row->updated_at ?? now()]
            );
        }
        $this->info("Synced {$items->count()} items.");

        // Stock Levels
        $stockLevels = DB::connection('inventory_dept')->table('stock_levels')->get();
        foreach ($stockLevels as $row) {
            \App\Models\InventoryDeptStockLevel::updateOrCreate(
                ['source_id' => $row->id],
                ['source_item_id' => $row->item_id ?? null, 'source_warehouse_id' => $row->warehouse_id ?? null, 'quantity_on_hand' => $row->quantity_on_hand ?? 0, 'reorder_threshold' => $row->reorder_threshold ?? 0, 'source_created_at' => $row->created_at ?? now(), 'source_updated_at' => $row->updated_at ?? now()]
            );
        }
        $this->info("Synced {$stockLevels->count()} stock levels.");

        // Stock Movements
        try {
            $movements = DB::connection('inventory_dept')->table('stock_movements')->get();
            foreach ($movements as $row) {
                DB::table('inventory_dept_stock_movements')->updateOrInsert(
                    ['source_id' => $row->id],
                    ['source_item_id' => $row->item_id ?? null, 'quantity' => $row->quantity ?? 0, 'movement_type' => $row->type ?? 'unknown', 'created_at' => now(), 'updated_at' => now()]
                );
            }
            $this->info("Synced {$movements->count()} stock movements.");
        } catch (\Throwable) { $this->warn('Stock movements table not available.'); }

        // Stock Receivings
        try {
            $receivings = DB::connection('inventory_dept')->table('stock_receivings')->get();
            foreach ($receivings as $row) {
                DB::table('inventory_dept_stock_receivings')->updateOrInsert(
                    ['source_id' => $row->id],
                    ['source_item_id' => $row->item_id ?? null, 'quantity' => $row->quantity ?? 0, 'received_date' => $row->received_date ?? now(), 'created_at' => now(), 'updated_at' => now()]
                );
            }
            $this->info("Synced {$receivings->count()} stock receivings.");
        } catch (\Throwable) { $this->warn('Stock receivings table not available.'); }

        $this->info('Inventory sync complete.');
        return self::SUCCESS;
    }
}
