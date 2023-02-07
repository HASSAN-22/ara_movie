<?php

namespace App\Policies;

use App\Models\MovieTag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use function App\Auxiliary\callPermission;

class MovieTagPolicy
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
        return $user->Admin() and callPermission(__METHOD__,'movietag',$user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MovieTag  $movieTag
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MovieTag $movieTag)
    {
        return $user->Admin() and callPermission(__METHOD__,$movieTag,$user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->Admin() and callPermission(__METHOD__,'movietag',$user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MovieTag  $movieTag
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MovieTag $movieTag)
    {
        return $user->Admin() and callPermission(__METHOD__,$movieTag,$user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MovieTag  $movieTag
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MovieTag $movieTag)
    {
        return $user->Admin() and callPermission(__METHOD__,$movieTag,$user);
    }

}
