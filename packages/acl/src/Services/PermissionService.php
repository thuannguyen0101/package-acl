<?php

namespace Workable\ACL\Services;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function getPermissions(array $filters = [])
    {
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

    private function buildQuery(array $filters = []): Builder
    {
        $with = [];
        if (isset($filters['with']['roles']) && (bool)$filters['with']['roles']) {
            $with = ['roles'];
        }

        return Permission::query()
            ->with($with);
    }
}
