<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use function App\Auxiliary\callPermission;

class CartPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->Admin() and callPermission(__METHOD__,'cart',$user);

    }


    public function view(User $user, Cart $cart)
    {
        return $user->Admin() and callPermission(__METHOD__,$cart,$user);
    }


    public function update(User $user, Cart $cart)
    {
        return $user->Admin() and callPermission(__METHOD__,$cart,$user);
    }

    ///////////////////////////////
    public function viewAnyUser(User $user)
    {
        return $user->User();
    }


    public function viewUser(User $user, Cart $cart)
    {
        return $user->User() and $cart->user_id == $cart->id;
    }


    public function createUser(User $user)
    {
        return $user->User();
    }
}
