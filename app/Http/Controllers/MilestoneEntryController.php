<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMilestoneEntryRequest;
use App\Http\Requests\UpdateMilestoneEntryRequest;
use App\Models\Baby;
use App\Models\MilestoneDefinition;
use App\Models\MilestoneEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MilestoneEntryController extends Controller
{
    public function index(Baby $baby): View
    {
        $this->authorize('view', $baby);

        $currentWeeks = $baby->age()->totalWeeks();

        return view('milestone-entries.index', [
            'baby' => $baby,
            'entries' => $baby->milestoneEntries()->get(),
            'upcoming' => MilestoneDefinition::query()
                ->where('age_max_weeks', '>=', $currentWeeks)
                ->orderBy('age_min_weeks')
                ->limit(6)
                ->get(),
        ]);
    }

    public function create(Baby $baby): View
    {
        $this->authorize('update', $baby);

        return view('milestone-entries.create', [
            'baby' => $baby,
            'definitions' => MilestoneDefinition::orderBy('age_min_weeks')->get(),
        ]);
    }

    public function store(StoreMilestoneEntryRequest $request, Baby $baby): RedirectResponse
    {
        $baby->milestoneEntries()->create($request->validated());

        $destination = $request->input('return') === 'guide'
            ? route('babies.guide', $baby)
            : route('babies.milestones.index', $baby);

        return redirect($destination)->with('status', 'Milestone recorded.');
    }

    public function edit(Baby $baby, MilestoneEntry $milestone): View
    {
        $this->authorize('update', $baby);
        abort_unless($milestone->baby_id === $baby->id, 404);

        return view('milestone-entries.edit', [
            'baby' => $baby,
            'entry' => $milestone,
            'definitions' => MilestoneDefinition::orderBy('age_min_weeks')->get(),
        ]);
    }

    public function update(UpdateMilestoneEntryRequest $request, Baby $baby, MilestoneEntry $milestone): RedirectResponse
    {
        abort_unless($milestone->baby_id === $baby->id, 404);

        $milestone->update($request->validated());

        return redirect()->route('babies.milestones.index', $baby)->with('status', 'Milestone updated.');
    }

    public function destroy(Baby $baby, MilestoneEntry $milestone): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($milestone->baby_id === $baby->id, 404);

        $milestone->delete();

        return redirect()->route('babies.milestones.index', $baby)->with('status', 'Milestone deleted.');
    }
}
