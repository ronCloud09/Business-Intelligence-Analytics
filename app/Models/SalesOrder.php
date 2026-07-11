<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $order_number
 * @property string $product_name
 * @property string|null $customer_segment
 * @property int $units_sold
 * @property float $revenue
 * @property Carbon $order_date
 * @property bool $is_new_customer
 */
#[Fillable(['order_number', 'product_name', 'customer_segment', 'units_sold', 'revenue', 'order_date', 'is_new_customer'])]
class SalesOrder extends Model
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
            'units_sold' => 'integer',
            'revenue' => 'decimal:2',
            'order_date' => 'date',
            'is_new_customer' => 'boolean',
        ];
    }
}
