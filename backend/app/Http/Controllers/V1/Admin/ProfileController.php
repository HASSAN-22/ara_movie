<?php

namespace App\Http\Controllers\V1\Admin;

use App\Auxiliary\Services\ProfileService;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProfileRequest;
use App\Models\User;

class ProfileController extends Controller
{

    public function getProfile(User $user){
        $this->authorize('viewProfile',$user);
        return ProfileService::getProfile($user);
    }

    public function updateProfile(ProfileRequest $request, User $user){
        $this->authorize('updateProfile',$user);
        return ProfileService::updateProfile($request, $user);
    }
}
