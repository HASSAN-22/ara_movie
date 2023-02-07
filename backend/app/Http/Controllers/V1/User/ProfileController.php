<?php

namespace App\Http\Controllers\V1\User;

use App\Auxiliary\Services\ProfileService;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getProfile(User $user){
        $this->authorize('viewUser',$user);
        return ProfileService::getProfile($user);
    }

    public function updateProfile(ProfileRequest $request, User $user){
        $this->authorize('updateUser',$user);
        return ProfileService::updateProfile($request, $user);
    }
}
