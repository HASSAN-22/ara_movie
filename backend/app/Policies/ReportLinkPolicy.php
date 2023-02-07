<?php

namespace App\Policies;

use App\Models\ReportLink;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

use function App\Auxiliary\callPermission;

class ReportLinkPolicy
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
        return $user->Admin() and callPermission(__METHOD__,'reportlink',$user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ReportLink $reportLink)
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
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ReportLink $reportLink)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ReportLink $reportLink)
    {
        return $user->Admin() and callPermission(__METHOD__,$reportLink,$user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ReportLink $reportLink)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReportLink  $reportLink
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ReportLink $reportLink)
    {
        //
    }
}
