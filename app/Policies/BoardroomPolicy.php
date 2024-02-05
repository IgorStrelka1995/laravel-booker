<?php

namespace App\Policies;

use App\Models\Boardroom;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoardroomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Boardroom  $boardroom
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Boardroom $boardroom)
    {
        return $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Boardroom  $boardroom
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Boardroom $boardroom)
    {
        return $user->is_admin;
    }
}
