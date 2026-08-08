<?php

namespace App\Http\Controllers;

use App\Models\Baby;
use App\Models\BabyStory;
use App\Services\ExifDateExtractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BabyStoryController extends Controller
{
    public function index(Baby $baby): View
    {
        $this->authorize('view', $baby);

        return view('baby-stories.index', [
            'baby' => $baby,
            'stories' => $baby->storyEntries()->paginate(20),
        ]);
    }

    public function create(Baby $baby): View
    {
        $this->authorize('update', $baby);

        return view('baby-stories.create', compact('baby'));
    }

    public function store(Request $request, Baby $baby): RedirectResponse
    {
        $this->authorize('update', $baby);

        $validated = $request->validate([
            'caption' => ['required_without:image', 'nullable', 'string'],
            'image' => ['required_without:caption', 'nullable', 'image', 'max:204800'],
            'occurred_at' => ['nullable', 'date'],
        ]);

        $imagePath = null;
        $occurredAt = $validated['occurred_at'] ?? null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('baby-stories', 'public');
            $occurredAt ??= ExifDateExtractor::extract($image);
        }

        $baby->storyEntries()->create([
            'caption' => $validated['caption'] ?? null,
            'image_path' => $imagePath,
            'occurred_at' => $occurredAt ?? now(),
        ]);

        return redirect()->route('babies.stories.index', $baby)->with('status', 'Story added.');
    }

    public function edit(Baby $baby, BabyStory $story): View
    {
        $this->authorize('update', $baby);
        abort_unless($story->baby_id === $baby->id, 404);

        return view('baby-stories.edit', ['baby' => $baby, 'story' => $story]);
    }

    public function update(Request $request, Baby $baby, BabyStory $story): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($story->baby_id === $baby->id, 404);

        $validated = $request->validate([
            'caption' => ['required_without:image', 'nullable', 'string'],
            'image' => ['nullable', 'image', 'max:204800'],
            'occurred_at' => ['required', 'date'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $imagePath = $story->image_path;

        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('baby-stories', 'public');
        } elseif ($request->boolean('remove_image') && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }

        if (! $validated['caption'] && ! $imagePath) {
            return back()->withErrors(['caption' => 'A story needs a caption or a photo.'])->withInput();
        }

        $story->update([
            'caption' => $validated['caption'] ?? null,
            'image_path' => $imagePath,
            'occurred_at' => $validated['occurred_at'],
        ]);

        return redirect()->route('babies.stories.index', $baby)->with('status', 'Story updated.');
    }

    public function destroy(Baby $baby, BabyStory $story): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($story->baby_id === $baby->id, 404);

        if ($story->image_path) {
            Storage::disk('public')->delete($story->image_path);
        }

        $story->delete();

        return redirect()->route('babies.stories.index', $baby)->with('status', 'Story deleted.');
    }
}
