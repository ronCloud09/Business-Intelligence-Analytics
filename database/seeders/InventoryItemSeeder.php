<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Seed inventory_items. Values for the well-known SKUs mirror the
     * numbers already shown in the dashboard/alerts UI (e.g. RTX 4060
     * at 32 units against a 50-unit threshold) so the live data matches
     * what the interface has been displaying.
     */
    public function run(): void
    {
        $items = [
            ['sku' => 'GPU-RTX4060', 'name' => 'RTX 4060 GPU', 'category' => 'GPUs', 'warehouse_zone' => 'Zone A', 'quantity_on_hand' => 32, 'reorder_threshold' => 50, 'unit_cost' => 15000],
            ['sku' => 'GPU-RTX4070', 'name' => 'RTX 4070 GPU', 'category' => 'GPUs', 'warehouse_zone' => 'Zone A', 'quantity_on_hand' => 48, 'reorder_threshold' => 40, 'unit_cost' => 22000],
            ['sku' => 'GPU-RX7800', 'name' => 'RX 7800 GPU', 'category' => 'GPUs', 'warehouse_zone' => 'Zone A', 'quantity_on_hand' => 55, 'reorder_threshold' => 30, 'unit_cost' => 19500],
            ['sku' => 'SSD-1TB-NVME', 'name' => '1TB NVMe SSD', 'category' => 'Storage', 'warehouse_zone' => 'Zone C', 'quantity_on_hand' => 18, 'reorder_threshold' => 25, 'unit_cost' => 1500],
            ['sku' => 'MON-27-QHD', 'name' => 'Gaming Monitor 27"', 'category' => 'Monitors', 'warehouse_zone' => 'Zone B', 'quantity_on_hand' => 90, 'reorder_threshold' => 20, 'unit_cost' => 1300],
            ['sku' => 'KB-MECH-01', 'name' => 'Mechanical Keyboard', 'category' => 'Accessories', 'warehouse_zone' => 'Zone C', 'quantity_on_hand' => 210, 'reorder_threshold' => 40, 'unit_cost' => 900],
            ['sku' => 'MS-PRO-01', 'name' => 'Gaming Mouse Pro', 'category' => 'Accessories', 'warehouse_zone' => 'Zone C', 'quantity_on_hand' => 260, 'reorder_threshold' => 40, 'unit_cost' => 600],
            ['sku' => 'CHAIR-PRO-01', 'name' => 'Gaming Chair Pro', 'category' => 'Furniture', 'warehouse_zone' => 'Zone B', 'quantity_on_hand' => 0, 'reorder_threshold' => 15, 'unit_cost' => 3000],
            ['sku' => 'HEADSET-USBC', 'name' => 'USB-C Headset', 'category' => 'Accessories', 'warehouse_zone' => 'Zone C', 'quantity_on_hand' => 140, 'reorder_threshold' => 30, 'unit_cost' => 800],
            ['sku' => 'ROUTER-WIFI6', 'name' => 'WiFi 6 Router', 'category' => 'Networking', 'warehouse_zone' => 'Zone B', 'quantity_on_hand' => 75, 'reorder_threshold' => 20, 'unit_cost' => 1500],
        ];

        foreach ($items as $item) {
            InventoryItem::create($item);
        }
    }
}
