<?php

namespace Workable\ACL\Services;

use Spatie\Permission\Models\Permission;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\Support\Traits\FilterBuilderTrait;

class PermissionService
{
    use FilterBuilderTrait;

    public function getPermissions(array $searches = [])
    {
        $filters = $this->getFilterRequest($searches);
        $query   = Permission::query();

        if (!empty($filters['with'])) {
            $query->with($filters['with']);
        }

        $permissions = $query->get();

        if ($permissions->count() === 0) {
            return [
                'status'      => ResponseMessageEnum::CODE_NO_CONTENT,
                'message'     => __('acl::api.no_data'),
                'permissions' => $permissions,
            ];
        }

        return [
            'status'      => ResponseMessageEnum::CODE_OK,
            'message'     => __('acl::api.success'),
            'permissions' => $permissions,
        ];
    }
}
