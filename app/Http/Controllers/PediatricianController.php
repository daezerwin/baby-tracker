<?php

namespace App\Http\Controllers;

use App\Http\Requests\PediatricianRequest;
use App\Models\Baby;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PediatricianController extends Controller
{
    public function edit(Baby $baby): View
    {
        $this->authorize('view', $baby);

        return view('pediatricians.edit', [
            'baby' => $baby,
            'pediatrician' => $baby->pediatrician,
        ]);
    }

    public function update(PediatricianRequest $request, Baby $baby): RedirectResponse
    {
        $baby->pediatrician()->updateOrCreate(['baby_id' => $baby->id], $request->validated());

        return redirect()->route('babies.pediatrician.edit', $baby)->with('status', 'Pediatrician info saved.');
    }
}
