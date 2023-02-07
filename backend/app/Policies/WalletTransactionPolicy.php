<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;
use function App\Auxiliary\callPermission;

class WalletTransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->Admin() and callPermission(__METHOD__,'wallettransaction',$user);    }

    public function view(User $user, WalletTransaction $walletTransaction)
    {
        //
    }

    public function create(User $user)
    {
        //
    }


    public function update(User $user, WalletTransaction $walletTransaction)
    {
        //
    }

    public function delete(User $user, WalletTransaction $walletTransaction)
    {
        //
    }

    /////////////////////////////

    public function viewAnyUser(User $user)
    {
        return $user->User();
    }

    public function viewUser(User $user, WalletTransaction $walletTransaction)
    {
        //
    }

    public function createUser(User $user)
    {
        return $user->User();
    }


    public function updateUser(User $user, WalletTransaction $walletTransaction)
    {
        //
    }

    public function deleteUser(User $user, WalletTransaction $walletTransaction)
    {
        //
    }

}
