<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserRightPolicy
{
    function canSeeAllUser(User $user)
    {
        return $user->role==="admin";
    }

    function canSeeAllUserInCompany(User $user)
    {
        return $user->role==="company" && $user->company_id!==null;
    }
}
