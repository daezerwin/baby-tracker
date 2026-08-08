<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['baby_id', 'path', 'caption', 'taken_at', 'is_profile'])]
class BabyPhoto extends Model
{
    protected function casts(): array
    {
        return [
            'taken_at' => 'date',
            'is_profile' => 'boolean',
        ];
    }

    public function baby(): BelongsTo
    {
        return $this->belongsTo(Baby::class);
    }

    public function url(): string
    {
        return asset('storage/'.$this->path);
    }
}
