<?php

namespace App\Http\Controllers;

use App\Models\Baby;
use App\Models\BabyStory;
use App\Services\ExifDateExtractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BabyStoryController extends Controller
{
    private const MEDIA_MIMES = 'jpg,jpeg,png,gif,webp,heic,heif,mp4,mov,webm,avi,m4v';

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
            'caption' => ['required_without:media', 'nullable', 'string'],
            'media' => ['required_without:caption', 'nullable', 'file', 'mimes:'.self::MEDIA_MIMES, 'max:512000'],
            'occurred_at' => ['nullable', 'date'],
        ]);

        $mediaPath = null;
        $mediaType = null;
        $occurredAt = $validated['occurred_at'] ?? null;

        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $mediaPath = $media->store('baby-stories', 'public');
            $mediaType = $this->mediaType($media);
            $occurredAt ??= ExifDateExtractor::extract($media);
        }

        $baby->storyEntries()->create([
            'caption' => $validated['caption'] ?? null,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
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
            'caption' => ['required_without:media', 'nullable', 'string'],
            'media' => ['nullable', 'file', 'mimes:'.self::MEDIA_MIMES, 'max:512000'],
            'occurred_at' => ['required', 'date'],
            'remove_media' => ['nullable', 'boolean'],
        ]);

        $mediaPath = $story->media_path;
        $mediaType = $story->media_type;

        if ($request->hasFile('media')) {
            if ($mediaPath) {
                Storage::disk('public')->delete($mediaPath);
            }
            $media = $request->file('media');
            $mediaPath = $media->store('baby-stories', 'public');
            $mediaType = $this->mediaType($media);
        } elseif ($request->boolean('remove_media') && $mediaPath) {
            Storage::disk('public')->delete($mediaPath);
            $mediaPath = null;
            $mediaType = null;
        }

        if (! $validated['caption'] && ! $mediaPath) {
            return back()->withErrors(['caption' => 'A story needs a caption or a photo/video.'])->withInput();
        }

        $story->update([
            'caption' => $validated['caption'] ?? null,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'occurred_at' => $validated['occurred_at'],
        ]);

        return redirect()->route('babies.stories.index', $baby)->with('status', 'Story updated.');
    }

    public function destroy(Baby $baby, BabyStory $story): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($story->baby_id === $baby->id, 404);

        if ($story->media_path) {
            Storage::disk('public')->delete($story->media_path);
        }

        $story->delete();

        return redirect()->route('babies.stories.index', $baby)->with('status', 'Story deleted.');
    }

    private function mediaType(UploadedFile $file): string
    {
        return str_starts_with($file->getMimeType() ?? '', 'video/') ? 'video' : 'image';
    }
}
