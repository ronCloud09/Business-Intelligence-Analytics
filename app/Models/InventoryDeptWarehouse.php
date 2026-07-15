<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryDeptWarehouse extends Model
{
    protected $fillable = [
        'source_id', 'name', 'province', 'city', 'barangay',
        'address_description', 'country', 'capacity_units', 'status',
    ];
}
