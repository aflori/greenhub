<?php

namespace App\Policies;

use App\Models\User;

class UserRightPolicy
{
    public function canSeeAllUser(User $user)
    {
        return $user->role === 'admin';
    }

    public function canSeeAllUserInCompany(User $user)
    {
        return $user->role === 'company' && $user->company_id !== null;
    }
}
