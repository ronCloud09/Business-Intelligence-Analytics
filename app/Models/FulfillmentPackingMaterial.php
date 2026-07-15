<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FulfillmentPackingMaterial extends Model
{
    protected $fillable = ['source_id', 'name', 'stock_qty', 'low_stock_threshold', 'is_box', 'box_size'];

    protected function casts(): array
    {
        return [
            'stock_qty' => 'integer',
            'low_stock_threshold' => 'integer',
            'is_box' => 'boolean',
        ];
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock_qty', '<=', 'low_stock_threshold');
    }
}
