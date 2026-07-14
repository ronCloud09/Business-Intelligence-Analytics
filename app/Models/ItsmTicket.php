<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $ticket_number
 * @property string $subject
 * @property string $priority
 * @property string $status
 * @property string|null $category
 * @property Carbon $opened_at
 * @property Carbon|null $resolved_at
 */
#[Fillable(['ticket_number', 'subject', 'priority', 'status', 'category', 'opened_at', 'resolved_at'])]
class ItsmTicket extends Model
{
    /** @use HasFactory<\Database\Factories\ItsmTicketFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * @param Builder<self> $query
     * @return Builder<self>
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['resolved', 'closed']);
    }
}
