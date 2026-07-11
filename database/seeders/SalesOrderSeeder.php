<?php

namespace Database\Seeders;

use App\Models\SalesOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SalesOrderSeeder extends Seeder
{
    /**
     * Seed sales_orders with the same top-selling products already
     * referenced in the dashboard's mock "Top 10 Products" table, spread
     * across the last 7 days so a revenue trend can be computed.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Gaming PC Alpha', 'units' => 240, 'revenue' => 480000],
            ['name' => 'RTX 4060 GPU', 'units' => 185, 'revenue' => 277500],
            ['name' => 'Gaming Monitor 27"', 'units' => 160, 'revenue' => 208000],
            ['name' => 'Mechanical Keyboard', 'units' => 145, 'revenue' => 130500],
            ['name' => 'Gaming Mouse Pro', 'units' => 132, 'revenue' => 79200],
            ['name' => 'USB-C Headset', 'units' => 118, 'revenue' => 94400],
            ['name' => '1TB NVMe SSD', 'units' => 105, 'revenue' => 157500],
            ['name' => 'Gaming Chair Pro', 'units' => 92, 'revenue' => 276000],
            ['name' => 'Webcam 4K', 'units' => 78, 'revenue' => 62400],
            ['name' => 'WiFi 6 Router', 'units' => 65, 'revenue' => 97500],
        ];

        $segments = ['Retail', 'Business', 'Education'];
        $orderCounter = 1;

        foreach ($products as $product) {
            $remainingUnits = $product['units'];
            $remainingRevenue = $product['revenue'];

            // Split each product's monthly totals into ~7 daily orders
            // so a trend chart has real day-by-day data.
            for ($day = 6; $day >= 0; $day--) {
                $isLastDay = $day === 0;
                $units = $isLastDay ? $remainingUnits : (int) round($product['units'] / 7);
                $revenue = $isLastDay ? $remainingRevenue : round($product['revenue'] / 7, 2);

                $remainingUnits -= $units;
                $remainingRevenue -= $revenue;

                SalesOrder::create([
                    'order_number' => 'SO-'.str_pad((string) $orderCounter, 5, '0', STR_PAD_LEFT),
                    'product_name' => $product['name'],
                    'customer_segment' => $segments[$orderCounter % count($segments)],
                    'units_sold' => max($units, 0),
                    'revenue' => max($revenue, 0),
                    'order_date' => Carbon::today()->subDays($day),
                    'is_new_customer' => $orderCounter % 4 === 0,
                ]);

                $orderCounter++;
            }
        }
    }
}
