<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $manufacturing_machine_id
 * @property Carbon $log_date
 * @property int $units_produced
 * @property int $units_target
 * @property int $defect_count
 * @property int $produced
 * @property int $target
 * @property int $defects
 */
#[Fillable(['manufacturing_machine_id', 'log_date', 'units_produced', 'units_target', 'defect_count'])]
class ManufacturingProductionLog extends Model
{
    /** @use HasFactory<\Database\Factories\ManufacturingProductionLogFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'log_date' => 'date',
            'units_produced' => 'integer',
            'units_target' => 'integer',
            'defect_count' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<ManufacturingMachine, $this>
     */
    public function machine(): BelongsTo
    {
        return $this->belongsTo(ManufacturingMachine::class, 'manufacturing_machine_id');
    }
}
