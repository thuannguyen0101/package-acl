<?php

namespace Workable\ACL\Http\Resources\Permission;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $filters  = $request->get("filters", []);
        $type     = $filters['type'] ?? null;
        $withRole = (isset($filters['with']['roles']) && (bool)$filters['with']['roles']);
        $dataRes  = [];

        $this->collection->transform(function ($item) use (&$dataRes, $withRole, $type) {
            $permissions = [
                'id'         => $item->id,
                'name'       => $item->name,
                'group'      => $item->group,
                'guard_name' => $item->guard_name,
            ];
            if ($withRole) {
                $permissions['roles'] = $item->roles->pluck('id')->toArray();
            }

            if ($type == "group") {
                $dataRes[$item->group][] = $permissions;
            } else {
                $dataRes[] = $permissions;
            }
        });

        return [
            'data' => [
                'permissions' => $dataRes
            ],
        ];
    }
}
