<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VipTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;
use function App\Auxiliary\callPermission;

class VipTransactionPolicy
{
    use HandlesAuthorization;
    public function viewAny(User $user)
    {
        return $user->Admin() and callPermission(__METHOD__,'viptransaction',$user);
    }
    //////////////////

    public function viewAnyUser(User $user)
    {
        return $user->User();
    }

    public function createUser(User $user)
    {
        return $user->User();
    }

}
