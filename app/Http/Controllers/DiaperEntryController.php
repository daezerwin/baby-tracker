<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiaperEntryRequest;
use App\Http\Requests\UpdateDiaperEntryRequest;
use App\Models\Baby;
use App\Models\DiaperEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiaperEntryController extends Controller
{
    public function index(Baby $baby): View
    {
        $this->authorize('view', $baby);

        return view('diaper-entries.index', [
            'baby' => $baby,
            'entries' => $baby->diaperEntries()->get(),
        ]);
    }

    public function create(Baby $baby): View
    {
        $this->authorize('update', $baby);

        return view('diaper-entries.create', compact('baby'));
    }

    public function store(StoreDiaperEntryRequest $request, Baby $baby): RedirectResponse
    {
        $baby->diaperEntries()->create([
            ...$request->safe()->except(['is_wet', 'is_dirty']),
            'is_wet' => $request->boolean('is_wet'),
            'is_dirty' => $request->boolean('is_dirty'),
        ]);

        return redirect()->route('babies.diapers.index', $baby)->with('status', 'Diaper change logged.');
    }

    public function edit(Baby $baby, DiaperEntry $diaper): View
    {
        $this->authorize('update', $baby);
        abort_unless($diaper->baby_id === $baby->id, 404);

        return view('diaper-entries.edit', ['baby' => $baby, 'entry' => $diaper]);
    }

    public function update(UpdateDiaperEntryRequest $request, Baby $baby, DiaperEntry $diaper): RedirectResponse
    {
        abort_unless($diaper->baby_id === $baby->id, 404);

        $diaper->update([
            ...$request->safe()->except(['is_wet', 'is_dirty']),
            'is_wet' => $request->boolean('is_wet'),
            'is_dirty' => $request->boolean('is_dirty'),
        ]);

        return redirect()->route('babies.diapers.index', $baby)->with('status', 'Diaper entry updated.');
    }

    public function destroy(Baby $baby, DiaperEntry $diaper): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($diaper->baby_id === $baby->id, 404);

        $diaper->delete();

        return redirect()->route('babies.diapers.index', $baby)->with('status', 'Diaper entry deleted.');
    }
}
