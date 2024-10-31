<?php

namespace Workable\ACL\Http\Resources;

class RoleResource extends BaseResource
{
    public function toArray($request): array
    {
        $permissions = $this->permissions->pluck('id')->toArray() ?? $this->getAllPermissions()->pluck('id')->toArray();
        return [

            "role" => [
                "id"          => $this->id,
                "name"        => $this->name,
                "guard_name"  => $this->guard_name,
                'permissions' => $permissions,
            ]
        ];
    }
}
