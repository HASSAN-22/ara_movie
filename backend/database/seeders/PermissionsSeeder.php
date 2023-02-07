<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    private $model;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        User::create([
//            'access'=>'admin',
//            'name'=>'armia',
//            'mobile'=>'09168963472',
//            'email'=>'armiaevil@gmail.com',
//            'password'=>bcrypt('12345678'),
//            'province_id'=>1,
//            'city_id'=>1,
//            'status'=>'yes',
//        ]);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions= array_merge(
        //    $this->permission('user'),
        //    $this->permission('category'),
        //    $this->permission('movie'),
        //    $this->permission('movielink'),
        //    $this->permission('movietag'),
        //    $this->permission('plan'),
        //    $this->permission('discount'),
        //    $this->permission('cart'),
        //    $this->permission('bankportal'),
        //    $this->permission('wallettransaction'),
        //    $this->permission('checkout'),
        //    $this->permission('viptransaction'),
        //    $this->permission('comment'),
        //    $this->permission('page'),
        //    $this->permission('configsite'),
        //    $this->permission('slider'),
           $this->permission('reportlink'),
        );
        Permission::insert($permissions);

        $user = User::first();
        foreach ($this->model as $m){
            $user->givePermissionTo([
                'view_any_'.$m,
                'view_'.$m,
                'create_'.$m,
                'update_'.$m,
                'delete_'.$m
            ]);
        }

    }

    private function permission($name){
        $this->model[] = $name;
        return [
            ['name'=>'view_any_'.$name,'guard_name'=>'web','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'view_'.$name,'guard_name'=>'web','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'create_'.$name,'guard_name'=>'web','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'update_'.$name,'guard_name'=>'web','created_at'=>now(),'updated_at'=>now()],
            ['name'=>'delete_'.$name,'guard_name'=>'web','created_at'=>now(),'updated_at'=>now()],
        ];
    }
}
