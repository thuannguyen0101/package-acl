<?php

namespace Workable\ACL\Http\Resources\Role;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Workable\ACL\Core\Traits\FilterApiTrait;

class RoleCollection extends ResourceCollection
{
    use FilterApiTrait;

    public function toArray($request): array
    {
        $relations = $this->getFilterRelationsApi($request->all());

        $roles = $this->collection->transform(function ($item) use ($relations) {
            $role = [
                'id'         => $item->id,
                'name'       => $item->name,
                'guard_name' => $item->guard_name,
            ];

            if (!empty($relations['with'])) {
                foreach ($relations['with'] as $relation) {
                    $role[$relation] = $item->$relation->makeHidden(['pivot', 'created_at', 'updated_at']);
                }
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
