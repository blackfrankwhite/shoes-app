<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return (bool) $user->is_admin;
    }

    public function view(User $user, Inquiry $inquiry): bool
    {
        return (bool) $user->is_admin;
    }

    public function update(User $user, Inquiry $inquiry): bool
    {
        return (bool) $user->is_admin;
    }
}
