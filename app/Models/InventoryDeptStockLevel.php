<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InventoryDeptStockLevel extends Model
{
    protected $fillable = [
        'source_id', 'source_item_id', 'source_warehouse_id',
        'quantity_on_hand', 'quantity_reserved', 'reorder_threshold',
    ];

    protected function casts(): array
    {
        return [
            'quantity_on_hand' => 'integer',
            'quantity_reserved' => 'integer',
            'reorder_threshold' => 'integer',
        ];
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('quantity_on_hand', '<=', 'reorder_threshold')
            ->where('quantity_on_hand', '>', 0);
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('quantity_on_hand', '<=', 0);
    }
}
