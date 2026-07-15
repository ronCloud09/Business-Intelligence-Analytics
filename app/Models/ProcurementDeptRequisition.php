<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementDeptRequisition extends Model
{
    protected $table = 'procurement_dept_requisitions';

    protected $fillable = [
        'source_id',
        'req_number',
        'item',
        'qty',
        'uom',
        'delivery_status',
        'department',
        'requested_by',
        'status',
        'date_requested',
        'notes',
        'source_created_at',
        'source_updated_at',
    ];
}