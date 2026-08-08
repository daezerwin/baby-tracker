<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBabyRequest;
use App\Http\Requests\UpdateBabyRequest;
use App\Models\Baby;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BabyController extends Controller
{
    public function index(): View
    {
        $babies = Auth::user()->babies()->latest()->get();

        return view('babies.index', compact('babies'));
    }

    public function create(): View
    {
        $this->authorize('create', Baby::class);

        return view('babies.create');
    }

    public function store(StoreBabyRequest $request): RedirectResponse
    {
        $baby = Auth::user()->babies()->create($request->validated());

        return redirect()->route('babies.show', $baby)->with('status', 'Baby profile created.');
    }

    public function show(Baby $baby): View
    {
        $this->authorize('view', $baby);

        session(['current_baby_id' => $baby->id]);

        $baby->load(['pediatrician']);

        return view('babies.show', [
            'baby' => $baby,
            'age' => $baby->age(),
            'lastFeed' => $baby->feedEntries()->first(),
            'lastDiaper' => $baby->diaperEntries()->first(),
            'lastSleep' => $baby->sleepEntries()->first(),
            'lastWeight' => $baby->weightEntries()->first(),
            'recentMilestones' => $baby->milestoneEntries()->limit(5)->get(),
        ]);
    }

    public function edit(Baby $baby): View
    {
        $this->authorize('update', $baby);

        return view('babies.edit', compact('baby'));
    }

    public function update(UpdateBabyRequest $request, Baby $baby): RedirectResponse
    {
        $baby->update($request->validated());

        return redirect()->route('babies.show', $baby)->with('status', 'Baby profile updated.');
    }

    public function destroy(Baby $baby): RedirectResponse
    {
        $this->authorize('delete', $baby);

        $baby->delete();

        return redirect()->route('babies.index')->with('status', 'Baby profile deleted.');
    }
}
