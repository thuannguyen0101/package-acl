<?php

namespace Workable\ACL\Http\Resources\Role;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $filters        = $request->get("filters", []);
        $withPermission = $filters['with']['permissions'] ?? null;

        $roles = $this->collection->transform(function ($item) use ($withPermission) {
            $role = [
                'id'         => $item->id,
                'name'       => $item->name,
                'group'      => $item->group,
                'guard_name' => $item->guard_name,
            ];

            if ($withPermission) {
                $role['permissions'] = $item->permissions->pluck('id')->toArray();
            }

            return $role;
        });

        return [
            'data' => [
                'roles' => $roles
            ],
        ];
    }
}
