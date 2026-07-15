<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceDeptPrebuiltConfig extends Model
{
    protected $table = 'ecommerce_dept_prebuilt_configs';

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'source_created_at' => 'datetime',
        'source_updated_at' => 'datetime',
    ];
}