<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Photos — {{ $baby->name }}" :back="route('babies.show', $baby)" />
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        @if (session('status'))
            <div class="rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif

        <x-card class="p-7">
            <form method="POST" action="{{ route('babies.photos.store', $baby) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="photos" value="Upload photos" />
                    <input id="photos" name="photos[]" type="file" accept="image/*" multiple required
                           class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100" />
                    <p class="text-xs text-gray-400 mt-1">Select multiple photos at once to upload them all together.</p>
                    <x-input-error :messages="$errors->get('photo')" class="mt-1" />
                    <x-input-error :messages="$errors->get('photos')" class="mt-1" />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="caption" value="Caption (optional)" />
                        <x-text-input id="caption" name="caption" type="text" class="mt-1 block w-full" />
                        <p class="text-xs text-gray-400 mt-1">Applied to every photo in this upload.</p>
                        <x-input-error :messages="$errors->get('caption')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="taken_at" value="Date taken (optional)" />
                        <x-text-input id="taken_at" name="taken_at" type="date" class="mt-1 block w-full" />
                        <p class="text-xs text-gray-400 mt-1">Leave blank to auto-detect from each photo's EXIF data, if available.</p>
                        <x-input-error :messages="$errors->get('taken_at')" class="mt-1" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-primary-button>Upload</x-primary-button>
                </div>
            </form>
        </x-card>

        @if ($photos->isEmpty())
            <x-card>
                <x-empty-state title="No photos yet" subtitle="Upload your first photo to start the gallery." />
            </x-card>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" x-data="{ lightbox: null }">
                @foreach ($photos as $photo)
                    <div class="relative group rounded-xl overflow-hidden bg-gray-100 aspect-square">
                        <img src="{{ $photo->url() }}" alt="{{ $photo->caption ?? $baby->name }}"
                             class="w-full h-full object-cover cursor-pointer"
                             x-on:click="lightbox = '{{ $photo->url() }}'">

                        @if ($photo->is_profile)
                            <span class="absolute top-1.5 left-1.5 text-[10px] bg-amber-400 text-gray-900 px-1.5 py-0.5 rounded-full font-semibold">Profile</span>
                        @endif

                        <div class="absolute inset-x-0 bottom-0 bg-black/50 opacity-0 group-hover:opacity-100 transition p-1.5 flex justify-between">
                            @unless ($photo->is_profile)
                                <form method="POST" action="{{ route('babies.photos.profile', [$baby, $photo]) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-[11px] text-white hover:text-blue-200">Set profile</button>
                                </form>
                            @else
                                <span></span>
                            @endunless
                            <form method="POST" action="{{ route('babies.photos.destroy', [$baby, $photo]) }}" onsubmit="return confirm('Delete this photo?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[11px] text-white hover:text-red-300">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach

                <div x-show="lightbox" x-on:click="lightbox = null" x-cloak
                     class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-6">
                    <img :src="lightbox" class="max-h-full max-w-full rounded-lg">
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
