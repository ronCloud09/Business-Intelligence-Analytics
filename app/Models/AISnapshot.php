<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The raw aggregated KPI payload (never raw DB rows) that was fed into
 * the AI provider for a given generation, kept for audit/reproducibility.
 *
 * @property int $id
 * @property int $ai_generation_id
 * @property array<string, mixed> $payload
 */
#[Fillable(['ai_generation_id', 'payload'])]
class AISnapshot extends Model
{
    protected $table = 'ai_snapshots';
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * @return BelongsTo<AIGeneration, $this>
     */
    public function generation(): BelongsTo
    {
        return $this->belongsTo(AIGeneration::class, 'ai_generation_id');
    }
}
