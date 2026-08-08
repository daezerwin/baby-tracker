<?php

namespace App\Http\Controllers;

use App\Models\Baby;
use Illuminate\Http\JsonResponse;

class AgeController extends Controller
{
    /**
     * Return a baby's current age breakdown (days/weeks/months/years).
     */
    public function show(Baby $baby): JsonResponse
    {
        $this->authorize('view', $baby);

        return response()->json($baby->age()->toArray());
    }
}
