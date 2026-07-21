<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * A single AI generation run. Every scheduled, manual, or event-driven
 * AI call creates one of these. Only one row ever has is_current = true;
 * the dashboard and chatbot always read the current one.
 *
 * @property int $id
 * @property int $generation_number
 * @property string $status
 * @property string $triggered_by
 * @property string|null $trigger_reason
 * @property string|null $provider
 * @property string|null $model
 * @property int $input_tokens
 * @property int $output_tokens
 * @property bool $is_current
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 * @property string|null $error_message
 */
#[Fillable([
    'generation_number',
    'status',
    'triggered_by',
    'trigger_reason',
    'provider',
    'model',
    'input_tokens',
    'output_tokens',
    'is_current',
    'started_at',
    'completed_at',
    'error_message',
])]
class AIGeneration extends Model
{
    protected $table = 'ai_generations';
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'input_tokens' => 'integer',
            'output_tokens' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<AIReport, $this>
     */
    public function reports(): HasMany
    {
        return $this->hasMany(AIReport::class, 'ai_generation_id');
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(AISnapshot::class, 'ai_generation_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AILog::class, 'ai_generation_id');
    }

    public function totalTokens(): int
    {
        return $this->input_tokens + $this->output_tokens;
    }
}
