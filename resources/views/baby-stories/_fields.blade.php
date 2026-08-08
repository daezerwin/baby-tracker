@php($story = $story ?? null)

<div>
    <x-input-label for="image" :value="$story?->image_path ? 'Replace photo (optional)' : 'Photo (optional)'" />
    <input id="image" name="image" type="file" accept="image/*"
           class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100" />
    <x-input-error :messages="$errors->get('image')" class="mt-1" />

    @if ($story?->image_path)
        <div class="mt-3 flex items-center gap-3">
            <img src="{{ $story->imageUrl() }}" alt="" class="w-20 h-20 rounded-lg object-cover">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                Remove this photo
            </label>
        </div>
    @endif
</div>

<div>
    <x-input-label for="caption" value="Caption" />
    <textarea id="caption" name="caption" rows="4"
              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600"
              placeholder="What happened?">{{ old('caption', $story?->caption) }}</textarea>
    <p class="text-xs text-gray-400 mt-1">A photo, a caption, or both — at least one is required.</p>
    <x-input-error :messages="$errors->get('caption')" class="mt-1" />
</div>

<div>
    <x-input-label for="occurred_at" value="When (optional)" />
    <x-text-input id="occurred_at" name="occurred_at" type="datetime-local" class="mt-1 block w-full"
        value="{{ old('occurred_at', $story?->occurred_at?->format('Y-m-d\TH:i')) }}" />
    <p class="text-xs text-gray-400 mt-1">Leave blank to auto-detect from the photo's EXIF data, if available, or default to now.</p>
    <x-input-error :messages="$errors->get('occurred_at')" class="mt-1" />
</div>
