<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentShipment extends Model
{
    protected $fillable = [
        'source_id', 'shipment_id', 'order_id', 'customer_name', 'product_name',
        'qty', 'courier', 'box_used', 'tracking_number', 'status', 'address',
        'due_date', 'amount', 'source_created_at', 'source_updated_at',
    ];
}
