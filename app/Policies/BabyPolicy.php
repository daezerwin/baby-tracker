<?php

namespace App\Policies;

use App\Models\Baby;
use App\Models\User;

class BabyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Baby $baby): bool
    {
        return $user->id === $baby->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Baby $baby): bool
    {
        return $user->id === $baby->user_id;
    }

    public function delete(User $user, Baby $baby): bool
    {
        return $user->id === $baby->user_id;
    }
}
