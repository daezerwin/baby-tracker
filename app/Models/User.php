<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Baby, $this>
     */
    public function babies()
    {
        return $this->hasMany(Baby::class);
    }

    /**
     * The baby currently selected for nav shortcuts (Growth/Guide), falling
     * back to the oldest baby on record if none has been explicitly picked.
     */
    public function currentBaby(): ?Baby
    {
        if ($babyId = session('current_baby_id')) {
            if ($baby = $this->babies()->find($babyId)) {
                return $baby;
            }
        }

        return $this->babies()->oldest()->first();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
