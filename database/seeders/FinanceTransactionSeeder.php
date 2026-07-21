<?php

namespace Database\Seeders;

use App\Models\FinanceTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FinanceTransactionSeeder extends Seeder
{
    /**
     * Seed finance_transactions with a realistic 30-day revenue/expense
     * history so Finance KPIs (revenue, profit margin, overdue payments)
     * have real numbers behind them.
     */
    public function run(): void
    {
        $revenueCategories = ['Hardware Sales', 'Service Contracts', 'Accessories', 'Extended Warranty'];
        $expenseCategories = ['Payroll', 'Supplier Payments', 'Logistics', 'Utilities', 'Marketing'];

        for ($day = 29; $day >= 0; $day--) {
            $date = Carbon::today()->subDays($day);

            FinanceTransaction::create([
                'type' => 'revenue',
                'category' => $revenueCategories[$day % count($revenueCategories)],
                'amount' => random_int(60000, 95000) + (random_int(0, 99) / 100),
                'currency' => 'PHP',
                'status' => 'paid',
                'transaction_date' => $date,
                'reference' => 'INV-'.$date->format('Ymd').'-'.random_int(100, 999),
            ]);

            FinanceTransaction::create([
                'type' => 'expense',
                'category' => $expenseCategories[$day % count($expenseCategories)],
                'amount' => random_int(30000, 55000) + (random_int(0, 99) / 100),
                'currency' => 'PHP',
                'status' => 'paid',
                'transaction_date' => $date,
                'reference' => 'EXP-'.$date->format('Ymd').'-'.random_int(100, 999),
            ]);
        }

        // A handful of overdue receivables so the Overdue Payments KPI is non-zero.
        FinanceTransaction::create([
            'type' => 'revenue',
            'category' => 'Service Contracts',
            'amount' => 48250.00,
            'currency' => 'PHP',
            'status' => 'overdue',
            'transaction_date' => Carbon::today()->subDays(21),
            'due_date' => Carbon::today()->subDays(6),
            'reference' => 'INV-OVERDUE-1',
        ]);

        FinanceTransaction::create([
            'type' => 'revenue',
            'category' => 'Hardware Sales',
            'amount' => 132400.00,
            'currency' => 'PHP',
            'status' => 'overdue',
            'transaction_date' => Carbon::today()->subDays(35),
            'due_date' => Carbon::today()->subDays(5),
            'reference' => 'INV-OVERDUE-2',
        ]);
    }
}
