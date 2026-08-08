<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['age_min_weeks', 'age_max_weeks', 'category', 'title', 'description'])]
class MilestoneDefinition extends Model
{
    public function milestoneEntries(): HasMany
    {
        return $this->hasMany(MilestoneEntry::class);
    }
}
