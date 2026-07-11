<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sku
 * @property string $name
 * @property string $category
 * @property string|null $warehouse_zone
 * @property int $quantity_on_hand
 * @property int $reorder_threshold
 * @property float $unit_cost
 */
#[Fillable(['sku', 'name', 'category', 'warehouse_zone', 'quantity_on_hand', 'reorder_threshold', 'unit_cost'])]
class InventoryItem extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity_on_hand' => 'integer',
            'reorder_threshold' => 'integer',
            'unit_cost' => 'decimal:2',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->quantity_on_hand > 0 && $this->quantity_on_hand <= $this->reorder_threshold;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity_on_hand <= 0;
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('quantity_on_hand', '<=', 'reorder_threshold')
            ->where('quantity_on_hand', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity_on_hand', '<=', 0);
    }
}
