<?php

namespace Workable\ACL\Services;

use Spatie\Permission\Models\Permission;
use Workable\ACL\Core\Traits\FilterApiTrait;

class PermissionService extends BaseService
{
    use FilterApiTrait;

    public function getPermissions(array $searches = [])
    {
        $filters = $this->getFilterRelationsApi($searches);

        return $this->buildQuery($filters)->get();
    }

    public function getPermission(int $id)
    {
        $permission = Permission::find($id);

        if (is_null($permission)) {
            return false;
        }
        return $permission;
    }

    public function updatePermission(int $id, array $data)
    {
        $permission = Permission::find($id);

        if (is_null($permission)) {
            return false;
        }

        $permission->syncRoles($data['role_ids']);

        return $permission;
    }

    private function buildQuery(array $filters = [])
    {
        $query = Permission::query();
        return $this->applyBaseRelationsWithFields($query, $filters);
    }
}
