<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementDeptCompany extends Model
{
    protected $table = 'procurement_dept_companies';

    protected $fillable = [
        'source_id',
        'company_name',
        'industry',
        'company_email',
        'phone_no',
        'admin_name',
        'admin_user_id',
        'employee_table_name',
        'status',
        'source_created_at',
        'source_updated_at',
    ];
}