<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Growth — {{ $baby->name }}" :back="route('babies.show', $baby)">
            <x-slot name="actions">
                <a href="{{ route('babies.weights.create', $baby) }}" wire:navigate><x-primary-button>+ Add Weight</x-primary-button></a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <x-card class="p-7">
            @if ($chartData->count() < 2)
                <x-empty-state title="Not enough data yet" subtitle="Log at least two weight entries to see the growth chart." />
            @else
                <p class="text-sm font-semibold text-gray-700 mb-3">Weight over time (kg)</p>
                <div class="relative" style="height: 280px;">
                    <canvas id="weight-chart"
                        data-labels="{{ $chartLabels->toJson() }}"
                        data-values="{{ $chartData->toJson() }}"></canvas>
                </div>
            @endif
        </x-card>

        <x-card class="p-7">
            <p class="text-sm font-semibold text-gray-700 mb-3">All entries</p>
            @if ($chartData->isEmpty())
                <x-empty-state title="No weight entries yet" />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="text-xs text-gray-500 uppercase tracking-wide border-b border-gray-100">
                                <th class="py-2 pr-4">Date</th>
                                <th class="py-2">Weight (kg)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($chartLabels as $i => $label)
                                <tr>
                                    <td class="py-2 pr-4 text-gray-700">{{ $label }}</td>
                                    <td class="py-2 text-gray-700">{{ $chartData[$i] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>
    </div>

    @if ($chartData->count() >= 2)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const canvas = document.getElementById('weight-chart');
                if (!canvas || !window.Chart) return;

                const labels = JSON.parse(canvas.dataset.labels);
                const values = JSON.parse(canvas.dataset.values);
                const ctx = canvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 280);
                gradient.addColorStop(0, 'rgba(29, 78, 216, 0.18)');
                gradient.addColorStop(1, 'rgba(29, 78, 216, 0.0)');

                new window.Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Weight (kg)',
                            data: values,
                            borderColor: '#1d4ed8',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#1d4ed8',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 1.5,
                            tension: 0.3,
                            fill: true,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                padding: 10,
                                cornerRadius: 8,
                                displayColors: false,
                            },
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#9ca3af', font: { size: 11 } },
                            },
                            y: {
                                grid: { color: '#f3f4f6' },
                                ticks: { color: '#9ca3af', font: { size: 11 } },
                            },
                        },
                    },
                });
            });
        </script>
    @endif
</x-app-layout>
