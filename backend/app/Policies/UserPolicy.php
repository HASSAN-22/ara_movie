<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

use function App\Auxiliary\callPermission;

class UserPolicy
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
        return $user->Admin() and callPermission(__METHOD__,'user',$user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        return $user->Admin() and callPermission(__METHOD__,$model,$user);
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->Admin() and callPermission(__METHOD__,'user',$user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        return $user->Admin() and callPermission(__METHOD__,$model,$user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        return $user->Admin() and callPermission(__METHOD__,$model,$user);
    }

    ////////////////////////////

    public function viewUser(User $user, User $model)
    {
        return $user->User() and $model->id == $user->id;
    }
    public function updateUser(User $user, User $model)
    {
        return $user->User() and $model->id == $user->id;
    }

    public function viewProfile(User $user, User $model)
    {
        return $user->Admin() and $model->id == $user->id;
    }
    public function updateProfile(User $user, User $model)
    {
        return $user->Admin() && $user->id == $model->id;
    }
}
