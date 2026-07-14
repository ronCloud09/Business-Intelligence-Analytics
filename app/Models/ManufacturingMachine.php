<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $line
 * @property string $status
 * @property int $downtime_minutes_today
 * @property float $production_rate
 * @property Carbon|null $last_status_change_at
 */
#[Fillable(['name', 'line', 'status', 'downtime_minutes_today', 'production_rate', 'last_status_change_at'])]
class ManufacturingMachine extends Model
{
    /** @use HasFactory<\Database\Factories\ManufacturingMachineFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'downtime_minutes_today' => 'integer',
            'production_rate' => 'decimal:2',
            'last_status_change_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<ManufacturingProductionLog, $this>
     */
    public function productionLogs(): HasMany
    {
        return $this->hasMany(ManufacturingProductionLog::class);
    }

    public function scopeDown(Builder $query): Builder
    {
        return $query->where('status', 'down');
    }
}
