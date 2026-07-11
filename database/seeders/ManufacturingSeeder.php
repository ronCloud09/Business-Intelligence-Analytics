<?php

namespace Database\Seeders;

use App\Models\ManufacturingMachine;
use App\Models\ManufacturingProductionLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ManufacturingSeeder extends Seeder
{
    /**
     * Seed manufacturing_machines and 7 days of production logs per
     * machine so downtime, production rate, and defect rate KPIs have
     * real numbers behind them.
     */
    public function run(): void
    {
        $machines = [
            ['name' => 'Assembly Line 1', 'line' => 'Line 1', 'status' => 'running', 'downtime_minutes_today' => 0, 'production_rate' => 96.5],
            ['name' => 'Assembly Line 2', 'line' => 'Line 2', 'status' => 'running', 'downtime_minutes_today' => 15, 'production_rate' => 91.2],
            ['name' => 'CNC Router 1', 'line' => 'Line 3', 'status' => 'down', 'downtime_minutes_today' => 240, 'production_rate' => 0.0, 'last_status_change_at' => Carbon::now()->subHours(4)],
            ['name' => 'CNC Router 2', 'line' => 'Line 3', 'status' => 'idle', 'downtime_minutes_today' => 60, 'production_rate' => 0.0],
            ['name' => 'Quality Control Station', 'line' => 'Line 1', 'status' => 'running', 'downtime_minutes_today' => 5, 'production_rate' => 98.0],
        ];

        $machineIds = [];
        foreach ($machines as $machine) {
            $machineIds[] = ManufacturingMachine::create($machine)->id;
        }

        foreach ($machineIds as $machineId) {
            for ($day = 6; $day >= 0; $day--) {
                $target = random_int(180, 220);
                $produced = (int) round($target * (random_int(85, 99) / 100));

                ManufacturingProductionLog::create([
                    'manufacturing_machine_id' => $machineId,
                    'log_date' => Carbon::today()->subDays($day),
                    'units_produced' => $produced,
                    'units_target' => $target,
                    'defect_count' => random_int(0, 8),
                ]);
            }
        }
    }
}
