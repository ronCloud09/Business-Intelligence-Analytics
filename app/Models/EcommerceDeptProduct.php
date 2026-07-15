<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EcommerceDeptProduct extends Model
{
    protected $table = 'ecommerce_dept_products';

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_sold_out' => 'boolean',
        'forge_points' => 'integer',
        'source_created_at' => 'datetime',
        'source_updated_at' => 'datetime',
    ];
}