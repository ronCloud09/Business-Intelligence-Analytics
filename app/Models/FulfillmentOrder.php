<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentOrder extends Model
{
    protected $fillable = [
        'source_id', 'customer_name', 'product_name', 'qty', 'status',
        'due_date', 'address', 'amount', 'source_created_at', 'source_updated_at',
    ];
}
