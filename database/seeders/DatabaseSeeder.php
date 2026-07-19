<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            FinanceTransactionSeeder::class,
            InventoryItemSeeder::class,
            ManufacturingSeeder::class,
            ProcurementOrderSeeder::class,
            ComplianceRiskSeeder::class,
            ItsmTicketSeeder::class,
            SalesOrderSeeder::class,
        ]);
    }
}