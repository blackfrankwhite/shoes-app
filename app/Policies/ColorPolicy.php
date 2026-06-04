<?php

namespace App\Policies;

use App\Models\Color;
use App\Models\User;

class ColorPolicy
{
    public function viewAny(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function create(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function update(User $user, Color $color): bool
    {
        return (bool) $user->is_admin;
    }

    public function delete(User $user, Color $color): bool
    {
        return (bool) $user->is_admin;
    }
}
