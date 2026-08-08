<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['baby_id', 'weight_kg', 'measured_at', 'notes'])]
class WeightEntry extends Model
{
    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'measured_at' => 'datetime',
        ];
    }

    public function baby(): BelongsTo
    {
        return $this->belongsTo(Baby::class);
    }
}
