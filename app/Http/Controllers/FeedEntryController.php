<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedEntryRequest;
use App\Http\Requests\UpdateFeedEntryRequest;
use App\Models\Baby;
use App\Models\FeedEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedEntryController extends Controller
{
    public function index(Baby $baby): View
    {
        $this->authorize('view', $baby);

        return view('feed-entries.index', [
            'baby' => $baby,
            'entries' => $baby->feedEntries()->get(),
        ]);
    }

    public function create(Baby $baby): View
    {
        $this->authorize('update', $baby);

        return view('feed-entries.create', compact('baby'));
    }

    public function store(StoreFeedEntryRequest $request, Baby $baby): RedirectResponse
    {
        $baby->feedEntries()->create($request->validated());

        return redirect()->route('babies.feeds.index', $baby)->with('status', 'Feed entry added.');
    }

    public function edit(Baby $baby, FeedEntry $feed): View
    {
        $this->authorize('update', $baby);
        abort_unless($feed->baby_id === $baby->id, 404);

        return view('feed-entries.edit', ['baby' => $baby, 'entry' => $feed]);
    }

    public function update(UpdateFeedEntryRequest $request, Baby $baby, FeedEntry $feed): RedirectResponse
    {
        abort_unless($feed->baby_id === $baby->id, 404);

        $feed->update($request->validated());

        return redirect()->route('babies.feeds.index', $baby)->with('status', 'Feed entry updated.');
    }

    public function destroy(Baby $baby, FeedEntry $feed): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($feed->baby_id === $baby->id, 404);

        $feed->delete();

        return redirect()->route('babies.feeds.index', $baby)->with('status', 'Feed entry deleted.');
    }
}
