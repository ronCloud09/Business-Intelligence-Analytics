<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManufacturingWorkOrder extends Model
{
    protected $fillable = [
        'source_id',
        'name',
        'specs',
        'status',
        'due',
        'source',
        'assigned',
        'source_created_at',
        'source_updated_at',
    ];
}
