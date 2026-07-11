<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string|null $standard
 * @property string $severity
 * @property string $status
 * @property string|null $description
 * @property Carbon $identified_date
 * @property Carbon|null $due_date
 */
#[Fillable(['title', 'standard', 'severity', 'status', 'description', 'identified_date', 'due_date'])]
class ComplianceRisk extends Model
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
            'identified_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', ['closed']);
    }

    public function scopeHighSeverity($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }
}
