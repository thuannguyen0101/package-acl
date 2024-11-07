<?php

namespace Workable\ACL\Services;

use Spatie\Permission\Models\Permission;
use Workable\ACL\Core\Traits\FilterApiTrait;
use Workable\ACL\Enums\ResponseMessageEnum;

class PermissionService extends BaseService
{
    use FilterApiTrait;

    public function getPermissions(array $searches = [])
    {
        $filters = $this->getFilterRelationsApi($searches);

        $permissions = $this->buildQuery($filters)->get();

        if ($permissions->count() === 0) {
            return [
                'status' => ResponseMessageEnum::CODE_NO_CONTENT,
                'message' => __('acl::api.no_data'),
                'permissions' => $permissions,
            ];
        }

        return [
            'status' => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.success'),
            'permissions' => $permissions,
        ];
    }

    private function buildQuery(array $filters = [])
    {
        $query = Permission::query();
        return $this->applyBaseRelationsWithFields($query, $filters);
    }
}
