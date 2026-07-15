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

        $companies = DB::connection('procurement_dept')
            ->table('companies')
            ->get();

        foreach ($companies as $row) {
            ProcurementDeptCompany::updateOrCreate(
                [
                    'source_id' => $row->id,
                ],
                [
                    'company_name' => $row->company_name,
                    'industry' => $row->industry,
                    'company_email' => $row->company_email,
                    'phone_no' => $row->phone_no,
                    'admin_name' => $row->admin_name,
                    'admin_user_id' => $row->admin_user_id,
                    'employee_table_name' => $row->employee_table_name,
                    'status' => $row->status,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }

        $this->info("Synced {$companies->count()} companies.");

        $requisitions = DB::connection('procurement_dept')
            ->table('requisitions')
            ->get();

        foreach ($requisitions as $row) {
            ProcurementDeptRequisition::updateOrCreate(
                [
                    'source_id' => $row->id,
                ],
                [
                    'req_number' => $row->req_number,
                    'item' => $row->item,
                    'qty' => $row->qty,
                    'uom' => $row->uom,
                    'delivery_status' => $row->delivery_status,
                    'department' => $row->department,
                    'requested_by' => $row->requested_by,
                    'status' => $row->status,
                    'date_requested' => $row->date_requested,
                    'notes' => $row->notes,
                    'source_created_at' => $row->created_at,
                    'source_updated_at' => $row->updated_at,
                ]
            );
        }

        $this->info("Synced {$requisitions->count()} requisitions.");

        $this->info('Procurement sync complete.');

        return self::SUCCESS;
    }
}