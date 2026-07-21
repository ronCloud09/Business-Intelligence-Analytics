<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManufacturingQcResult extends Model
{
    protected $fillable = [
        'source_id',
        'source_session_id',
        'check_id',
        'value',
        'verdict',
        'note',
        'source_created_at',
        'source_updated_at',
    ];
}
