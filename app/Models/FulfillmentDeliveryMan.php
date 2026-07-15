<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FulfillmentDeliveryMan extends Model
{
    protected $fillable = ['source_id', 'age', 'license_num', 'vehicle_type', 'shipping_provider_id'];
}
