<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $type
 * @property string $category
 * @property float $amount
 * @property float $total
 * @property string $currency
 * @property string $status
 * @property Carbon $transaction_date
 * @property Carbon|null $due_date
 * @property string|null $reference
 * @property string|null $notes
 */
#[Fillable(['type', 'category', 'amount', 'currency', 'status', 'transaction_date', 'due_date', 'reference', 'notes'])]
class FinanceTransaction extends Model
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
            'amount' => 'decimal:2',
            'transaction_date' => 'date',
            'due_date' => 'date',
        ];
    }

    /**
     * @param Builder<self> $query
     * @return Builder<self>
     */
    public function scopeRevenue(Builder $query): Builder
    {
        return $query->where('type', 'revenue');
    }

    /**
     * @param Builder<self> $query
     * @return Builder<self>
     */
    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', 'expense');
    }

    /**
     * @param Builder<self> $query
     * @return Builder<self>
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'overdue');
    }
}
