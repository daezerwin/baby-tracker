@props(['baby', 'size' => 'w-14 h-14', 'iconSize' => 'w-4 h-4', 'textSize' => 'text-xl'])

<form method="POST" action="{{ route('babies.photos.store', $baby) }}" enctype="multipart/form-data" class="relative shrink-0">
    @csrf
    <input type="hidden" name="set_as_profile" value="1">
    <label class="{{ $size }} rounded-full overflow-hidden bg-blue-100 flex items-center justify-center cursor-pointer relative block">
        @if ($baby->profile_photo_path)
            <img src="{{ asset('storage/'.$baby->profile_photo_path) }}" alt="{{ $baby->name }}" class="w-full h-full object-cover">
        @else
            <span class="{{ $textSize }} font-semibold text-blue-600">{{ Str::substr($baby->name, 0, 1) }}</span>
        @endif
        <input type="file" name="photo" accept="image/*" class="sr-only" onchange="this.form.requestSubmit()">
    </label>
    <span class="absolute -bottom-1 -right-1 {{ $iconSize }} bg-amber-400 rounded-full flex items-center justify-center ring-2 ring-white pointer-events-none">
        <svg class="w-2.5 h-2.5 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5V8.25A1.5 1.5 0 014.5 6.75h2.379a1.5 1.5 0 001.06-.44l1.122-1.12a1.5 1.5 0 011.06-.44h3.758a1.5 1.5 0 011.06.44l1.122 1.12a1.5 1.5 0 001.06.44H19.5a1.5 1.5 0 011.5 1.5v8.25a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 16.5z" />
        </svg>
    </span>
</form>
