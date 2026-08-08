<?php

namespace App\Http\Controllers;

use App\Models\Baby;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;
use Throwable;

class ImportController extends Controller
{
    public function show(Baby $baby): View
    {
        $this->authorize('view', $baby);

        return view('imports.show', compact('baby'));
    }

    public function importDiapers(Request $request, Baby $baby): RedirectResponse
    {
        $this->authorize('update', $baby);

        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt']]);

        [$imported, $skipped] = $this->eachRow($request->file('file'), function (array $row) use ($baby) {
            $occurredAt = $row['occurred_at'] ?? $row['date'] ?? null;
            $isWet = $this->toBool($row['pee'] ?? $row['is_wet'] ?? null);
            $isDirty = $this->toBool($row['poop'] ?? $row['is_dirty'] ?? null);

            if (! $occurredAt || (! $isWet && ! $isDirty)) {
                return false;
            }

            $baby->diaperEntries()->create([
                'occurred_at' => Carbon::parse($occurredAt),
                'is_wet' => $isWet,
                'is_dirty' => $isDirty,
                'consistency' => $row['consistency'] ?? null,
                'notes' => $row['notes'] ?? null,
            ]);

            return true;
        });

        return redirect()->route('babies.import.show', $baby)
            ->with('status', "Imported {$imported} diaper entries.".($skipped ? " Skipped {$skipped} invalid rows." : ''));
    }

    public function importFeeds(Request $request, Baby $baby): RedirectResponse
    {
        $this->authorize('update', $baby);

        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt']]);

        [$imported, $skipped] = $this->eachRow($request->file('file'), function (array $row) use ($baby) {
            $fedAt = $row['fed_at'] ?? $row['date'] ?? null;

            if (! $fedAt) {
                return false;
            }

            $amount = $row['amount_oz'] ?? $row['amount'] ?? null;

            $baby->feedEntries()->create([
                'type' => 'bottle',
                'fed_at' => Carbon::parse($fedAt),
                'amount_oz' => $amount !== null && $amount !== '' ? (float) $amount : null,
                'duration_minutes' => $row['duration_minutes'] ?? null,
                'notes' => $row['notes'] ?? null,
            ]);

            return true;
        });

        return redirect()->route('babies.import.show', $baby)
            ->with('status', "Imported {$imported} bottle feed entries.".($skipped ? " Skipped {$skipped} invalid rows." : ''));
    }

    /**
     * Read a CSV file's header + rows and hand each row (as an assoc array
     * keyed by lower-cased header) to the callback. Returns [imported, skipped].
     */
    private function eachRow(UploadedFile $file, callable $callback): array
    {
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return [0, 0];
        }

        $header = array_map(fn ($column) => strtolower(trim((string) $column)), $header);

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($header)) {
                $skipped++;

                continue;
            }

            $data = array_map(fn ($value) => is_string($value) ? trim($value) : $value, array_combine($header, $row));

            try {
                if ($callback($data)) {
                    $imported++;
                } else {
                    $skipped++;
                }
            } catch (Throwable) {
                $skipped++;
            }
        }

        fclose($handle);

        return [$imported, $skipped];
    }

    private function toBool(mixed $value): bool
    {
        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes', 'y'], true);
    }
}
