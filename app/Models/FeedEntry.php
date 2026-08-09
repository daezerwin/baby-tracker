<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['baby_id', 'type', 'fed_at', 'amount_oz', 'duration_minutes', 'side', 'notes'])]
class FeedEntry extends Model
{
    protected function casts(): array
    {
        return [
            'fed_at' => 'datetime',
            'amount_oz' => 'decimal:1',
        ];
    }

    public function baby(): BelongsTo
    {
        return $this->belongsTo(Baby::class);
    }

    public function formattedAmount(): ?string
    {
        if ($this->amount_oz === null) {
            return null;
        }

        return rtrim(rtrim((string) $this->amount_oz, '0'), '.').'oz';
    }
}
