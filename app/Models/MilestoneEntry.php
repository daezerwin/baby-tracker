<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['baby_id', 'milestone_definition_id', 'title', 'category', 'achieved_on', 'notes'])]
class MilestoneEntry extends Model
{
    protected function casts(): array
    {
        return [
            'achieved_on' => 'date',
        ];
    }

    public function baby(): BelongsTo
    {
        return $this->belongsTo(Baby::class);
    }

    public function milestoneDefinition(): BelongsTo
    {
        return $this->belongsTo(MilestoneDefinition::class);
    }
}
