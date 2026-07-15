<?php

namespace App\Console\Commands;

use App\Models\ManufacturingQcResult;
use App\Models\ManufacturingWorkOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncManufacturingData extends Command
{
    protected $signature = 'sync:manufacturing';

    protected $description = 'Pull work orders and QC results from the Manufacturing department database into our local tables';

    public function handle(): int
    {
        $this->info('Syncing manufacturing_dept -> local tables...');

        $workOrders = DB::connection('manufacturing_dept')->table('work_orders')->get();

        foreach ($workOrders as $row) {
            ManufacturingWorkOrder::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'name' => $row->name,
                    'specs' => $row->specs,
                    'status' => $row->status,
                    'due' => $row->due,
                    'source' => $row->source,
                    'assigned' => $row->assigned,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }

        $this->info("Synced {$workOrders->count()} work orders.");

        $qcResults = DB::connection('manufacturing_dept')->table('qc_results')->get();

        foreach ($qcResults as $row) {
            ManufacturingQcResult::updateOrCreate(
                ['source_id' => $row->id],
                [
                    'source_session_id' => $row->session_id,
                    'check_id' => $row->check_id,
                    'value' => $row->value,
                    'verdict' => $row->verdict,
                    'note' => $row->note,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }

        $this->info("Synced {$qcResults->count()} QC results.");

        $this->info('Manufacturing sync complete.');

        return self::SUCCESS;
    }
}
