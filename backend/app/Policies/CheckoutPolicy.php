<?php

namespace App\Policies;

use App\Models\Checkout;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use function App\Auxiliary\callPermission;

class CheckoutPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->Admin() and callPermission(__METHOD__,'checkout',$user);
    }


    public function view(User $user, Checkout $checkout)
    {
        return $user->Admin() and callPermission(__METHOD__,'checkout',$user);
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Checkout $checkout)
    {
        return $user->Admin() and callPermission(__METHOD__,'checkout',$user);
    }

    public function delete(User $user, Checkout $checkout)
    {
        //
    }

    ////////////////////////////
    public function viewAnyUser(User $user)
    {
        return $user->User();
    }


    public function viewUser(User $user, Checkout $checkout)
    {
        return $user->User() and $checkout->user_id == $user->id;
    }

    public function createUser(User $user)
    {
        return $user->User();
    }

    public function updateUser(User $user, Checkout $checkout)
    {
        return $user->User() and $checkout->user_id == $user->id;
    }

    public function deleteUser(User $user, Checkout $checkout)
    {
        //
    }
}
