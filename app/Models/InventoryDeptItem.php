<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryDeptItem extends Model
{
    protected $fillable = ['source_id', 'sku', 'name', 'source_category_id', 'unit_cost'];

    protected function casts(): array
    {
        return [
            'unit_cost' => 'decimal:2',
        ];
    }
}
