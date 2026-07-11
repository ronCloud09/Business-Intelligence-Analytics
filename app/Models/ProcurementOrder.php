<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $po_number
 * @property string $supplier
 * @property string $item_description
 * @property int $quantity
 * @property float $total_cost
 * @property string $status
 * @property Carbon|null $expected_date
 * @property bool $expedited
 */
#[Fillable(['po_number', 'supplier', 'item_description', 'quantity', 'total_cost', 'status', 'expected_date', 'expedited'])]
class ProcurementOrder extends Model
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
            'quantity' => 'integer',
            'total_cost' => 'decimal:2',
            'expected_date' => 'date',
            'expedited' => 'boolean',
        ];
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', ['received', 'cancelled']);
    }
}
