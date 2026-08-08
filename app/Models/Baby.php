<?php

namespace App\Models;

use App\Services\AgeCalculator;
use Database\Factories\BabyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'name', 'sex', 'date_of_birth', 'time_of_birth',
    'birth_weight_kg', 'birth_length_cm', 'blood_type',
    'profile_photo_path', 'notes',
])]
class Baby extends Model
{
    /** @use HasFactory<BabyFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'birth_weight_kg' => 'decimal:2',
            'birth_length_cm' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function weightEntries(): HasMany
    {
        return $this->hasMany(WeightEntry::class)->orderByDesc('measured_at');
    }

    public function feedEntries(): HasMany
    {
        return $this->hasMany(FeedEntry::class)->orderByDesc('fed_at');
    }

    public function diaperEntries(): HasMany
    {
        return $this->hasMany(DiaperEntry::class)->orderByDesc('occurred_at');
    }

    public function sleepEntries(): HasMany
    {
        return $this->hasMany(SleepEntry::class)->orderByDesc('started_at');
    }

    public function milestoneEntries(): HasMany
    {
        return $this->hasMany(MilestoneEntry::class)->orderByDesc('achieved_on');
    }

    public function pediatrician(): HasOne
    {
        return $this->hasOne(Pediatrician::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(BabyPhoto::class)->orderByDesc('taken_at');
    }

    public function storyEntries(): HasMany
    {
        return $this->hasMany(BabyStory::class)->orderByDesc('occurred_at');
    }

    public function age(): AgeCalculator
    {
        return new AgeCalculator($this->date_of_birth);
    }
}
