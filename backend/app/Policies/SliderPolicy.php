<?php

namespace App\Policies;

use App\Models\Slider;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

use function App\Auxiliary\callPermission;

class SliderPolicy
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
        return $user->Admin() and callPermission(__METHOD__,'slider',$user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Slider $slider)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->Admin() and callPermission(__METHOD__,'slider',$user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Slider $slider)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Slider $slider)
    {
        return $user->Admin() and callPermission(__METHOD__,$slider,$user);
    }

}
