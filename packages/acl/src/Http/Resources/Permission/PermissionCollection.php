<?php

namespace Workable\ACL\Http\Resources\Permission;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Workable\ACL\Core\Traits\FilterApiTrait;

class PermissionCollection extends ResourceCollection
{
    use FilterApiTrait;

    public function toArray($request): array
    {
        $filters   = $request->get("filters", []);
        $type      = $filters["type"] ?? null;
        $relations = $this->getFilterRelationsApi($request->all());

        $this->collection->transform(function ($item) use (&$dataRes, $relations, $type) {
            $permissions = [
                'id'         => $item->id,
                'name'       => $item->name,
                'group'      => $item->group,
                'guard_name' => $item->guard_name,
            ];

            if (!empty($relations['with'])) {
                foreach ($relations['with'] as $relation) {
                    $permissions[$relation] = $item->$relation->makeHidden(['pivot', 'created_at', 'updated_at']);
                }
            }

            if ($type == "group") {
                $dataRes[$item->group][] = $permissions;
            } else {
                $dataRes[] = $permissions;
            }
        });

        return ['permissions' => $dataRes];
    }
}
