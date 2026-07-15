<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentPackingError extends Model
{
    protected $fillable = ['source_id', 'order_id', 'material', 'reason', 'source_created_at', 'source_updated_at'];
}
