<?php

namespace App\Http\Controllers;

use App\Models\AgeGuide;
use App\Models\Baby;
use App\Models\MilestoneDefinition;
use Illuminate\View\View;

class GuideController extends Controller
{
    public function show(Baby $baby): View
    {
        $this->authorize('view', $baby);

        $currentWeeks = $baby->age()->totalWeeks();

        $guide = AgeGuide::query()
            ->where('age_min_weeks', '<=', $currentWeeks)
            ->where('age_max_weeks', '>=', $currentWeeks)
            ->first();

        $milestones = MilestoneDefinition::query()
            ->where('age_min_weeks', '<=', $currentWeeks + 4)
            ->where('age_max_weeks', '>=', $currentWeeks)
            ->orderBy('age_min_weeks')
            ->get();

        return view('guide.show', [
            'baby' => $baby,
            'guide' => $guide,
            'milestones' => $milestones,
            'achievedMilestoneIds' => $baby->milestoneEntries()->pluck('milestone_definition_id')->filter()->all(),
        ]);
    }
}
