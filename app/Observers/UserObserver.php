<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function updating(User $user): void
    {
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    }

    public function deleting(User $user): void
    {
        $user->email_verified_at = null;
        $user->save();
    }
}
