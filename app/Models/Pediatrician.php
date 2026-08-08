<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'baby_id', 'clinic_name', 'doctor_name', 'phone',
    'email', 'address', 'next_appointment_at', 'notes',
])]
class Pediatrician extends Model
{
    protected function casts(): array
    {
        return [
            'next_appointment_at' => 'datetime',
        ];
    }

    public function baby(): BelongsTo
    {
        return $this->belongsTo(Baby::class);
    }
}
