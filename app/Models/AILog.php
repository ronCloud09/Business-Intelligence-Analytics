<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $ai_generation_id
 * @property string $level
 * @property string $message
 * @property array<string, mixed>|null $context
 */
#[Fillable(['ai_generation_id', 'level', 'message', 'context'])]
class AILog extends Model
{
    protected $table= 'ai_logs';
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'context' => 'array',
        ];
    }

    /**
     * @return BelongsTo<AIGeneration, $this>
     */
    public function generation(): BelongsTo
    {
        return $this->belongsTo(AIGeneration::class, 'ai_generation_id');
    }

    public function scopeErrors($query)
    {
        return $query->where('level', 'error');
    }
}
