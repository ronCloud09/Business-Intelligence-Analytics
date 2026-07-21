<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // GPUs
            [
                'sku' => 'GPU-RTX4060',
                'name' => 'RTX 4060 GPU',
                'category' => 'GPUs',
                'warehouse_zone' => 'Zone A',
                'quantity_on_hand' => 32,
                'reorder_threshold' => 50,
                'unit_cost' => 15000,
            ],
            [
                'sku' => 'GPU-RTX4070',
                'name' => 'RTX 4070 GPU',
                'category' => 'GPUs',
                'warehouse_zone' => 'Zone A',
                'quantity_on_hand' => 48,
                'reorder_threshold' => 40,
                'unit_cost' => 22000,
            ],
            [
                'sku' => 'GPU-RX7800',
                'name' => 'RX 7800 GPU',
                'category' => 'GPUs',
                'warehouse_zone' => 'Zone A',
                'quantity_on_hand' => 55,
                'reorder_threshold' => 30,
                'unit_cost' => 19500,
            ],

            // CPUs
            [
                'sku' => 'CPU-R9-9950X',
                'name' => 'Ryzen 9 9950X',
                'category' => 'CPUs',
                'warehouse_zone' => 'Zone A',
                'quantity_on_hand' => 5,
                'reorder_threshold' => 20,
                'unit_cost' => 42000,
            ],
            [
                'sku' => 'CPU-I9-14900K',
                'name' => 'Intel Core i9 14900K',
                'category' => 'CPUs',
                'warehouse_zone' => 'Zone A',
                'quantity_on_hand' => 8,
                'reorder_threshold' => 15,
                'unit_cost' => 35000,
            ],

            // Storage
            [
                'sku' => 'SSD-1TB-NVME',
                'name' => '1TB NVMe SSD',
                'category' => 'Storage',
                'warehouse_zone' => 'Zone C',
                'quantity_on_hand' => 18,
                'reorder_threshold' => 25,
                'unit_cost' => 1500,
            ],
            [
                'sku' => 'SSD-2TB-NVME',
                'name' => '2TB NVMe SSD',
                'category' => 'Storage',
                'warehouse_zone' => 'Zone C',
                'quantity_on_hand' => 0,
                'reorder_threshold' => 20,
                'unit_cost' => 4800,
            ],

            // Memory
            [
                'sku' => 'RAM-32GB-DDR5',
                'name' => '32GB DDR5 RAM',
                'category' => 'Memory',
                'warehouse_zone' => 'Zone C',
                'quantity_on_hand' => 12,
                'reorder_threshold' => 50,
                'unit_cost' => 6500,
            ],

            // Power Supplies
            [
                'sku' => 'PSU-850W-GOLD',
                'name' => '850W Gold Power Supply',
                'category' => 'Power Supplies',
                'warehouse_zone' => 'Zone B',
                'quantity_on_hand' => 3,
                'reorder_threshold' => 25,
                'unit_cost' => 7200,
            ],

            // Cooling
            [
                'sku' => 'COOLER-AIO-360',
                'name' => '360mm AIO Liquid Cooler',
                'category' => 'Cooling',
                'warehouse_zone' => 'Zone A',
                'quantity_on_hand' => 7,
                'reorder_threshold' => 15,
                'unit_cost' => 8500,
            ],

            // Monitors
            [
                'sku' => 'MON-27-QHD',
                'name' => 'Gaming Monitor 27"',
                'category' => 'Monitors',
                'warehouse_zone' => 'Zone B',
                'quantity_on_hand' => 90,
                'reorder_threshold' => 20,
                'unit_cost' => 1300,
            ],
            [
                'sku' => 'MON-32-4K',
                'name' => '32-inch 4K Monitor',
                'category' => 'Monitors',
                'warehouse_zone' => 'Zone B',
                'quantity_on_hand' => 180,
                'reorder_threshold' => 15,
                'unit_cost' => 28000,
            ],

            // Accessories
            [
                'sku' => 'KB-MECH-01',
                'name' => 'Mechanical Keyboard',
                'category' => 'Accessories',
                'warehouse_zone' => 'Zone C',
                'quantity_on_hand' => 210,
                'reorder_threshold' => 40,
                'unit_cost' => 900,
            ],
            [
                'sku' => 'MS-PRO-01',
                'name' => 'Gaming Mouse Pro',
                'category' => 'Accessories',
                'warehouse_zone' => 'Zone C',
                'quantity_on_hand' => 260,
                'reorder_threshold' => 40,
                'unit_cost' => 600,
            ],
            [
                'sku' => 'HEADSET-USBC',
                'name' => 'USB-C Headset',
                'category' => 'Accessories',
                'warehouse_zone' => 'Zone C',
                'quantity_on_hand' => 140,
                'reorder_threshold' => 30,
                'unit_cost' => 800,
            ],

            // Furniture
            [
                'sku' => 'CHAIR-PRO-01',
                'name' => 'Gaming Chair Pro',
                'category' => 'Furniture',
                'warehouse_zone' => 'Zone B',
                'quantity_on_hand' => 0,
                'reorder_threshold' => 15,
                'unit_cost' => 3000,
            ],

            // Networking
            [
                'sku' => 'ROUTER-WIFI6',
                'name' => 'WiFi 6 Router',
                'category' => 'Networking',
                'warehouse_zone' => 'Zone B',
                'quantity_on_hand' => 75,
                'reorder_threshold' => 20,
                'unit_cost' => 1500,
            ],
        ];

        foreach ($items as $item) {
            InventoryItem::create($item);
        }
    }
}