<?php

namespace App\Console\Commands;

use App\Models\FinanceDeptInvoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncFinanceData extends Command
{
    protected $signature = 'sync:finance';

    protected $description = 'Sync Finance department invoices';

    public function handle(): int
    {
        $this->info('Syncing finance_dept -> local tables...');

        // Invoices — primary key is invoice_id, not id
        $invoices = DB::connection('finance_dept')->table('invoice')->get();
        foreach ($invoices as $row) {
            \App\Models\FinanceDeptInvoice::updateOrCreate(
                ['source_id' => $row->invoice_id],
                [
                    'invoice_number' => 'INV-' . $row->invoice_id,
                    'invoice_amount' => $row->invoice_amount ?? 0,
                    'paid_amount' => $row->paid_amount ?? 0,
                    'outstanding_amount' => $row->outstanding_amount ?? 0,
                    'status' => $row->status ?? 'Pending',
                    'issue_date' => $row->issue_date ?? now(),
                    'source_created_at' => $row->created_at ?? now(),
                    'source_updated_at' => $row->updated_at ?? now(),
                ]
            );
        }
        $this->info("Synced {$invoices->count()} invoices.");

        // Expenses — monthly summary with category breakdowns
        try {
            $expenses = DB::connection('finance_dept')->table('expenses')->get();
            foreach ($expenses as $row) {
                DB::table('finance_dept_expenses')->updateOrInsert(
                    ['source_id' => $row->id],
                    [
                        'month' => $row->month ?? now()->format('Y-m'),
                        'total_expenses' => $row->total_expenses ?? 0,
                        'percent_change' => $row->percent_change ?? 0,
                        'budget_used' => $row->budget_used ?? 0,
                        'budget_total' => $row->budget_total ?? 0,
                        'manufacturing' => $row->manufacturing ?? 0,
                        'procurement' => $row->procurement ?? 0,
                        'inventory' => $row->inventory ?? 0,
                        'order_fulfillment' => $row->order_fulfillment ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
            $this->info("Synced {$expenses->count()} expense summaries.");
        } catch (\Throwable) { $this->warn('Expenses table not available.'); }

        // Sales — monthly summary
        try {
            $sales = DB::connection('finance_dept')->table('sales')->get();
            foreach ($sales as $row) {
                DB::table('finance_dept_sales')->updateOrInsert(
                    ['source_id' => $row->id],
                    [
                        'total_sales' => $row->total_sales ?? 0,
                        'percent_change' => $row->percent_change ?? 0,
                        'difference_from_last_month' => $row->difference_from_last_month ?? 0,
                        'period' => $row->period ?? now()->format('Y-m'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
            $this->info("Synced {$sales->count()} sales summaries.");
        } catch (\Throwable) { $this->warn('Sales table not available.'); }

        $this->info('Finance sync complete.');
        return self::SUCCESS;
    }
}