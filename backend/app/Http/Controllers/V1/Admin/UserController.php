<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserRequest;
use App\Http\Resources\V1\UserResourceCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function App\Auxiliary\avatar;
use function App\Auxiliary\removeFile;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',User::class);
        $users = User::latest()->paginate();
        return new UserResourceCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);
        $user = User::create([
            'name'=>$request->name,
            'access'=>$request->access,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'status'=>$request->status,
            'password'=>Hash::make($request->password),
            'avatar'=>avatar($request->name)
        ]);
        return $user ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }


    /**
     * Show the form for showing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return response(['status'=>'success', 'data'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $user->name = $request->name;
        $user->access = $request->access;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->status = $request->status;
        $user->password = !empty($request->password) ? Hash::make($request->password) : $user->password;

        return $user->save() ? response(['status'=>'success']) : response(['status'=>'error'],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        if($user->delete()){
            removeFile($user->avatar);
            return response(['status'=>'success']) ;
        }
        return response(['status'=>'error'],500);
    }

    public function permission(User $user){
        $this->authorize('viewAny', User::class);
        return response(['status'=>'success','data'=>$user->getAllPermissions(),'user'=>$user]);
    }

    public function updatePermission(Request $request ,User $user){
        $this->authorize('create', User::class);
        $user->syncPermissions($request->permissions);
        return response(['status'=>'success']);
    }
}
