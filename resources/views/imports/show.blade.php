<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Import CSV — {{ $baby->name }}" :back="route('babies.show', $baby)" />
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        @if (session('status'))
            <div class="rounded-xl bg-emerald-50 text-emerald-700 text-sm px-4 py-3">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-xl bg-red-50 text-red-700 text-sm px-4 py-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-card class="p-7">
            <p class="text-lg font-semibold text-gray-800">Diaper changes (pee &amp; poop)</p>
            <p class="text-sm text-gray-500 mt-1">
                CSV with columns: <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">occurred_at</code>,
                <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">pee</code> (1/0),
                <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">poop</code> (1/0),
                and optionally <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">consistency</code>, <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">notes</code>.
            </p>
            <p class="text-xs text-gray-400 mt-2 font-mono">occurred_at,pee,poop<br>2026-08-01 08:30,1,0<br>2026-08-01 12:15,1,1</p>

            <form method="POST" action="{{ route('babies.import.diapers', $baby) }}" enctype="multipart/form-data" class="mt-5 flex flex-wrap items-center gap-3">
                @csrf
                <input type="file" name="file" accept=".csv,text/csv" required
                       class="block text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100">
                <x-primary-button>Import Diapers</x-primary-button>
            </form>
        </x-card>

        <x-card class="p-7">
            <p class="text-lg font-semibold text-gray-800">Bottle feeds</p>
            <p class="text-sm text-gray-500 mt-1">
                CSV with columns: <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">fed_at</code>,
                <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">amount_oz</code>,
                and optionally <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">duration_minutes</code>, <code class="text-xs bg-gray-100 px-1.5 py-0.5 rounded">notes</code>.
                All imported rows are logged as bottle feeds.
            </p>
            <p class="text-xs text-gray-400 mt-2 font-mono">fed_at,amount_oz<br>2026-08-01 09:00,3.5<br>2026-08-01 13:00,4</p>

            <form method="POST" action="{{ route('babies.import.feeds', $baby) }}" enctype="multipart/form-data" class="mt-5 flex flex-wrap items-center gap-3">
                @csrf
                <input type="file" name="file" accept=".csv,text/csv" required
                       class="block text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100">
                <x-primary-button>Import Bottle Feeds</x-primary-button>
            </form>
        </x-card>
    </div>
</x-app-layout>
