<?php


namespace App\Auxiliary\Services;


class UserPermission
{
    /**
     * Check user permissions
     * @param $methodName
     * @param $model
     * @param $user
     * @return bool
     */
    public static function permission($methodName, $model, $user){
        $model = strtolower(last(explode('\\',$model)));
        $name = strtolower(last(preg_replace('/\B([A-Z])/', '_$1',explode('::',$methodName)))).'_'.$model;
        $permissions = $user->permissions()->pluck('name')->toArray();
        return in_array($name,$permissions);
    }
}
