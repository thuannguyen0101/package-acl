<?php

namespace Workable\ACL\Core\Traits;

use Illuminate\Contracts\Auth\Authenticatable;

trait CheckPermissionTrait
{
    protected function checkPermission(string $permission): bool
    {
        if ($permission){
            return false;
        }
        $user = auth()->user();
        if (!$user){
            return false;
        }

        return $user->can($permission);
    }
    protected function checkRole(string $role): bool
    {
        if ($role){
            return false;
        }
        $user = auth()->user();
        if (!$user){
            return false;
        }

        return $user->hasRole($role);
    }

    public function getUser(): ?Authenticatable
    {
        return auth()->user();
    }
}
