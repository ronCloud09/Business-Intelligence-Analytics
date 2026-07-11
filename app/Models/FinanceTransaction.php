<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $type
 * @property string $category
 * @property float $amount
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

    public function scopeRevenue($query)
    {
        return $query->where('type', 'revenue');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }
}
