<?php

namespace App\Policies;

use App\Models\MovieLink;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use function App\Auxiliary\callPermission;

class MovieLinkPolicy
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
        return $user->Admin() and callPermission(__METHOD__,'movielink',$user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MovieLink  $movieLink
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MovieLink $movieLink)
    {
        return $user->Admin() and callPermission(__METHOD__,$movieLink,$user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->Admin() and callPermission(__METHOD__,'movielink',$user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MovieLink  $movieLink
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MovieLink $movieLink)
    {
        return $user->Admin() and callPermission(__METHOD__,$movieLink,$user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MovieLink  $movieLink
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, MovieLink $movieLink)
    {
        return $user->Admin() and callPermission(__METHOD__,$movieLink,$user);
    }

}
