<?php

namespace App\Policies;

use App\Models\Size;
use App\Models\User;

class SizePolicy
{
    public function viewAny(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function create(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function update(User $user, Size $size): bool
    {
        return (bool) $user->is_admin;
    }

    public function delete(User $user, Size $size): bool
    {
        return (bool) $user->is_admin;
    }
}
