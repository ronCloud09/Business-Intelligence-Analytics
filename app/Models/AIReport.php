<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One report "section" (Executive Summary, Top Recommendations, Risk
 * Analysis, Business Health, or a single Department Insight) belonging
 * to an AIGeneration. All five are produced from ONE AI request per
 * generation and stored as separate rows for easy per-section reads.
 *
 * @property int $id
 * @property int $ai_generation_id
 * @property string $type
 * @property string|null $department
 * @property array<string, mixed> $content
 */
#[Fillable(['ai_generation_id', 'type', 'department', 'content'])]
class AIReport extends Model
{
    protected $table = 'ai_reports';
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    /**
     * @return BelongsTo<AIGeneration, $this>
     */
    public function generation(): BelongsTo
    {
        return $this->belongsTo(AIGeneration::class, 'ai_generation_id');
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeCurrent($query)
    {
        return $query->whereHas('generation', fn($q) => $q->where('is_current', true));
    }
}
