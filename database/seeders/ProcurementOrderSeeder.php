<?php

namespace Database\Seeders;

use App\Models\ProcurementOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProcurementOrderSeeder extends Seeder
{
    /**
     * Seed procurement_orders, including PO #4521 — the same order
     * number already referenced in the dashboard's "GPU Stock Critical"
     * alert — so the Procurement tab lines up with the rest of the UI.
     */
    public function run(): void
    {
        $orders = [
            ['po_number' => 'PO-4521', 'supplier' => 'TechDistro Inc.', 'item_description' => 'RTX 4060 GPU restock', 'quantity' => 100, 'total_cost' => 1500000, 'status' => 'submitted', 'expected_date' => Carbon::today()->addDays(5), 'expedited' => true],
            ['po_number' => 'PO-4522', 'supplier' => 'Global Components Ltd.', 'item_description' => '1TB NVMe SSD restock', 'quantity' => 150, 'total_cost' => 225000, 'status' => 'approved', 'expected_date' => Carbon::today()->addDays(9), 'expedited' => false],
            ['po_number' => 'PO-4510', 'supplier' => 'Peripheral Supply Co.', 'item_description' => 'Mechanical keyboards', 'quantity' => 300, 'total_cost' => 270000, 'status' => 'in_transit', 'expected_date' => Carbon::today()->addDays(2), 'expedited' => false],
            ['po_number' => 'PO-4498', 'supplier' => 'Furniture Direct', 'item_description' => 'Gaming chairs', 'quantity' => 40, 'total_cost' => 120000, 'status' => 'received', 'expected_date' => Carbon::today()->subDays(3), 'expedited' => false],
            ['po_number' => 'PO-4480', 'supplier' => 'Networking Partners', 'item_description' => 'WiFi 6 routers', 'quantity' => 80, 'total_cost' => 120000, 'status' => 'received', 'expected_date' => Carbon::today()->subDays(10), 'expedited' => false],
        ];

        foreach ($orders as $order) {
            ProcurementOrder::create($order);
        }
    }
}
