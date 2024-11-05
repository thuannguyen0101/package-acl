<?php

namespace Workable\ACL\Http\Resources\Permission;

use Workable\ACL\Http\Resources\BaseResource;

class PermissionResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'permission' => [
                'id'         => $this->id,
                'name'       => $this->name,
                'group'      => $this->group,
                'guard_name' => $this->guard_name,
                'roles'      => $this->roles()->pluck('id')->toArray(),
            ]
        ];
    }
}
