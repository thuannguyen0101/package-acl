<?php

namespace Workable\ACL\Http\Resources\Permission;

use Workable\ACL\Core\Traits\FilterApiTrait;
use Workable\ACL\Http\Resources\BaseResource;

class PermissionResource extends BaseResource
{
    use FilterApiTrait;

    public function toArray($request): array
    {
        $relations = $this->getFilterRelationsApi($request->all());

        $dataRes = [
            'permission' => [
                'id'         => $this->id,
                'name'       => $this->name,
                'group'      => $this->group,
                'guard_name' => $this->guard_name,
            ]
        ];

        if (!empty($relations['with'])) {
            foreach ($relations['with'] as $relation) {
                $dataRes['permission'][$relation] = $this->$relation->makeHidden(['pivot', 'created_at', 'updated_at']);
            }
        }

        return $dataRes;
    }
}
