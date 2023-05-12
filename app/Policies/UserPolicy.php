<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function updateRole(User $user, User $model)
    {
        return $user->id !== $model->id;
    }

}