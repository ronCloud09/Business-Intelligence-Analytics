<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceDeptConfiguratorConfig extends Model
{
    protected $table = 'ecommerce_dept_configurator_configs';

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
        'source_created_at' => 'datetime',
        'source_updated_at' => 'datetime',
    ];
}