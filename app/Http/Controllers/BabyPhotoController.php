<?php

namespace App\Http\Controllers;

use App\Models\Baby;
use App\Models\BabyPhoto;
use App\Services\ExifDateExtractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BabyPhotoController extends Controller
{
    public function index(Baby $baby): View
    {
        $this->authorize('view', $baby);

        return view('baby-photos.index', [
            'baby' => $baby,
            'photos' => $baby->photos()->get(),
        ]);
    }

    public function store(Request $request, Baby $baby): RedirectResponse
    {
        $this->authorize('update', $baby);

        $validated = $request->validate([
            'photo' => ['required_without:photos', 'nullable', 'image', 'max:8192'],
            'photos' => ['required_without:photo', 'nullable', 'array', 'min:1'],
            'photos.*' => ['image', 'max:8192'],
            'caption' => ['nullable', 'string', 'max:255'],
            'taken_at' => ['nullable', 'date'],
        ]);

        $files = $request->hasFile('photos') ? $request->file('photos') : [$request->file('photo')];

        $lastPhoto = null;

        foreach ($files as $file) {
            $takenAt = $validated['taken_at'] ?? ExifDateExtractor::extract($file) ?? now();

            $path = $file->store('baby-photos', 'public');

            $lastPhoto = $baby->photos()->create([
                'path' => $path,
                'caption' => $validated['caption'] ?? null,
                'taken_at' => $takenAt,
            ]);
        }

        if ($request->boolean('set_as_profile') && $lastPhoto) {
            $baby->photos()->whereKeyNot($lastPhoto->id)->update(['is_profile' => false]);
            $lastPhoto->update(['is_profile' => true]);
            $baby->update(['profile_photo_path' => $lastPhoto->path]);
        }

        $count = count($files);

        return back(fallback: route('babies.photos.index', $baby))
            ->with('status', $count === 1 ? 'Photo uploaded.' : "{$count} photos uploaded.");
    }

    public function setProfile(Baby $baby, BabyPhoto $photo): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($photo->baby_id === $baby->id, 404);

        $baby->photos()->update(['is_profile' => false]);
        $photo->update(['is_profile' => true]);
        $baby->update(['profile_photo_path' => $photo->path]);

        return redirect()->route('babies.photos.index', $baby)->with('status', 'Profile photo updated.');
    }

    public function destroy(Baby $baby, BabyPhoto $photo): RedirectResponse
    {
        $this->authorize('update', $baby);
        abort_unless($photo->baby_id === $baby->id, 404);

        Storage::disk('public')->delete($photo->path);

        if ($baby->profile_photo_path === $photo->path) {
            $baby->update(['profile_photo_path' => null]);
        }

        $photo->delete();

        return redirect()->route('babies.photos.index', $baby)->with('status', 'Photo deleted.');
    }
}
