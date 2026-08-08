<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['baby_id', 'is_wet', 'is_dirty', 'consistency', 'occurred_at', 'notes'])]
class DiaperEntry extends Model
{
    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'is_wet' => 'boolean',
            'is_dirty' => 'boolean',
        ];
    }

    public function baby(): BelongsTo
    {
        return $this->belongsTo(Baby::class);
    }

    public function label(): string
    {
        if ($this->is_wet && $this->is_dirty) {
            return 'Pee & Poop';
        }

        return $this->is_dirty ? 'Poop' : 'Pee';
    }
}
