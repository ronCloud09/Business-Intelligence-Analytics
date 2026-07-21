<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceDeptInvoice extends Model
{
    protected $table = 'finance_dept_invoices';

    protected $fillable = [
        'source_id',
        'client_id',
        'issue_date',
        'due_date',
        'payment_date',
        'invoice_amount',
        'paid_amount',
        'outstanding_amount',
        'status',
        'source_created_at',
        'source_updated_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'source_created_at' => 'datetime',
        'source_updated_at' => 'datetime',
    ];
}