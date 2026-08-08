@php($entry = $entry ?? null)

<div x-data="{
        definitions: {{ $definitions->map(fn ($d) => ['id' => $d->id, 'title' => $d->title, 'category' => $d->category])->values()->toJson() }},
        definitionId: '{{ old('milestone_definition_id', $entry?->milestone_definition_id) }}',
        title: '{{ old('title', $entry?->title) }}',
        category: '{{ old('category', $entry?->category) }}',
        applyDefinition() {
            const def = this.definitions.find(d => String(d.id) === String(this.definitionId));
            if (def) { this.title = def.title; this.category = def.category; }
        }
     }" class="space-y-5">

    <div>
        <x-input-label for="milestone_definition_id" value="Choose a common milestone (optional)" />
        <select id="milestone_definition_id" name="milestone_definition_id" x-model="definitionId" x-on:change="applyDefinition()"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
            <option value="">Custom milestone…</option>
            @foreach ($definitions as $definition)
                <option value="{{ $definition->id }}">{{ $definition->title }} ({{ ucfirst($definition->category) }})</option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="title" value="Milestone" />
        <input id="title" name="title" type="text" x-model="title" required
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600" />
        <x-input-error :messages="$errors->get('title')" class="mt-1" />
    </div>

    <div>
        <x-input-label for="category" value="Category (optional)" />
        <select id="category" name="category" x-model="category"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">
            <option value="">—</option>
            @foreach (['motor' => 'Motor', 'cognitive' => 'Cognitive', 'social' => 'Social', 'language' => 'Language'] as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category')" class="mt-1" />
    </div>
</div>

<div>
    <x-input-label for="achieved_on" value="Achieved On" />
    <x-text-input id="achieved_on" name="achieved_on" type="date" class="mt-1 block w-full" required
        value="{{ old('achieved_on', $entry?->achieved_on?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" />
    <x-input-error :messages="$errors->get('achieved_on')" class="mt-1" />
</div>

<div>
    <x-input-label for="notes" value="Notes (optional)" />
    <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm text-base py-2.5 px-3.5 focus:border-blue-600 focus:ring-blue-600">{{ old('notes', $entry?->notes) }}</textarea>
    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
</div>
