<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSleepEntryRequest;
use App\Http\Requests\UpdateSleepEntryRequest;
use App\Models\Baby;
use App\Models\SleepEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SleepEntryController extends Controller
{
    public function index(Baby $baby): View
    {
        $this->authorize('view', $baby);

        return view('sleep-entries.index', [
            'baby' => $baby,
            'entries' => $baby->sleepEntries()->get(),
        ]);
    }

    public function create(Baby $baby): View
    {
        $this->authorize('update', $baby);

        return view('sleep-entries.create', compact('baby'));
    }

    public function store(StoreSleepEntryRequest $request, Baby $baby): RedirectResponse
    {
        $baby->sleepEntries()->create($request->validated());

        return redirect()->route('babies.sleeps.index', $baby)->with('status', 'Sleep entry added.');
    }

    public function edit(Baby $baby, SleepEntry $sleep): View
    {
        $this->authorize('update', $baby);
        abort_unless($sleep->baby_id === $baby->id, 404);

        return view('sleep-entries.edit', ['baby' => $baby, 'entry' => $sleep]);
    }

    public function update(UpdateSleepEntryRequest $request, Baby $baby, SleepEntry $sleep): RedirectResponse
    {
        abort_unless($sleep->baby_id === $baby->id, 404);

        $sleep->update($request->validated());

        return redirect()->route('babies.sleeps.index', $baby)->with('status', 'Sleep entry updated.');
    }

    public function destroy(Baby $baby, SleepEntry $sleep): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($sleep->baby_id === $baby->id, 404);

        $sleep->delete();

        return redirect()->route('babies.sleeps.index', $baby)->with('status', 'Sleep entry deleted.');
    }
}
