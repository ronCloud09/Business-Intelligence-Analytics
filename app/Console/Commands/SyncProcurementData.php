<?php

namespace App\Console\Commands;

use App\Models\ProcurementDeptCompany;
use App\Models\ProcurementDeptRequisition;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncProcurementData extends Command
{
    protected $signature = 'sync:procurement';

    protected $description = 'Pull procurement data into local mirror tables';

    public function handle(): int
    {
        $this->info('Syncing procurement_dept -> local tables...');

        // Companies (suppliers)
        try {
            $companies = DB::connection('procurement_dept')->table('companies')->get();
            foreach ($companies as $row) {
                \App\Models\ProcurementDeptCompany::updateOrCreate(
                    ['source_id' => $row->id],
                    ['name' => $row->name ?? '', 'contact' => $row->contact ?? '', 'email' => $row->email ?? '', 'source_created_at' => $row->created_at ?? now(), 'source_updated_at' => $row->updated_at ?? now()]
                );
            }
            $this->info("Synced {$companies->count()} companies.");
        } catch (\Throwable) { $this->warn('Companies table not available.'); }

        // Requisitions
        try {
            $requisitions = DB::connection('procurement_dept')->table('requisitions')->get();
            foreach ($requisitions as $row) {
                \App\Models\ProcurementDeptRequisition::updateOrCreate(
                    ['source_id' => $row->id],
                    ['item_description' => $row->description ?? $row->item_name ?? '', 'quantity' => $row->quantity ?? 0, 'status' => $row->status ?? 'pending', 'source_created_at' => $row->created_at ?? now(), 'source_updated_at' => $row->updated_at ?? now()]
                );
            }
            $this->info("Synced {$requisitions->count()} requisitions.");
        } catch (\Throwable) { $this->warn('Requisitions table not available.'); }

        $this->info('Procurement sync complete.');
        return self::SUCCESS;
    }
}