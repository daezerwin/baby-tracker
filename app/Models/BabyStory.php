<?php

namespace App\Models;

use Database\Factories\BabyStoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['baby_id', 'caption', 'image_path', 'occurred_at'])]
class BabyStory extends Model
{
    /** @use HasFactory<BabyStoryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
        ];
    }

    public function baby(): BelongsTo
    {
        return $this->belongsTo(Baby::class);
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? asset('storage/'.$this->image_path) : null;
    }
}
