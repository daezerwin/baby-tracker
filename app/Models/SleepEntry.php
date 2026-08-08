<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['baby_id', 'started_at', 'ended_at', 'notes'])]
class SleepEntry extends Model
{
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function baby(): BelongsTo
    {
        return $this->belongsTo(Baby::class);
    }

    public function durationMinutes(): ?int
    {
        if (! $this->ended_at) {
            return null;
        }

        return (int) $this->started_at->diffInMinutes($this->ended_at);
    }
}
