<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWeightEntryRequest;
use App\Http\Requests\UpdateWeightEntryRequest;
use App\Models\Baby;
use App\Models\WeightEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WeightEntryController extends Controller
{
    public function index(Baby $baby): View
    {
        $this->authorize('view', $baby);

        return view('weight-entries.index', [
            'baby' => $baby,
            'entries' => $baby->weightEntries()->get(),
        ]);
    }

    public function chart(Baby $baby): View
    {
        $this->authorize('view', $baby);

        $entries = $baby->weightEntries()->orderBy('measured_at')->get();

        return view('weight-entries.chart', [
            'baby' => $baby,
            'chartLabels' => $entries->map(fn (WeightEntry $entry) => $entry->measured_at->format('M j, Y')),
            'chartData' => $entries->map(fn (WeightEntry $entry) => (float) $entry->weight_kg),
        ]);
    }

    public function create(Baby $baby): View
    {
        $this->authorize('update', $baby);

        return view('weight-entries.create', compact('baby'));
    }

    public function store(StoreWeightEntryRequest $request, Baby $baby): RedirectResponse
    {
        $baby->weightEntries()->create($request->validated());

        return redirect()->route('babies.weights.index', $baby)->with('status', 'Weight entry added.');
    }

    public function edit(Baby $baby, WeightEntry $weight): View
    {
        $this->authorize('update', $baby);
        abort_unless($weight->baby_id === $baby->id, 404);

        return view('weight-entries.edit', ['baby' => $baby, 'entry' => $weight]);
    }

    public function update(UpdateWeightEntryRequest $request, Baby $baby, WeightEntry $weight): RedirectResponse
    {
        abort_unless($weight->baby_id === $baby->id, 404);

        $weight->update($request->validated());

        return redirect()->route('babies.weights.index', $baby)->with('status', 'Weight entry updated.');
    }

    public function destroy(Baby $baby, WeightEntry $weight): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($weight->baby_id === $baby->id, 404);

        $weight->delete();

        return redirect()->route('babies.weights.index', $baby)->with('status', 'Weight entry deleted.');
    }
}
