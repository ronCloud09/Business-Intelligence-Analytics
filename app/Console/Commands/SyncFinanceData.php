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

        $rows = DB::connection('finance_dept')
            ->table('invoice')
            ->get();

        foreach ($rows as $row) {
            FinanceDeptInvoice::updateOrCreate(
                ['source_id' => $row->invoice_id],
                [
                    'client_id' => $row->client_id,
                    'issue_date' => $row->issue_date,
                    'due_date' => $row->due_date,
                    'payment_date' => $row->payment_date,
                    'invoice_amount' => $row->invoice_amount,
                    'paid_amount' => $row->paid_amount,
                    'outstanding_amount' => $row->outstanding_amount,
                    'status' => $row->status,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }

        $this->info("Synced {$rows->count()} invoices.");
        $this->info('Finance sync complete.');

        return self::SUCCESS;
    }
}