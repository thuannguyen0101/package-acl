<?php

namespace Workable\ACL\Services;

use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function getPermissions(): array
    {
        $listGroupPermission = Permission::with('roles')->get()->groupBy('group')->toArray();
        $dataPermission      = [];
        foreach ($listGroupPermission as $key => $permissions) {
            foreach ($permissions as $permission) {
                if (count($permission['roles']) > 0) {
                    $permission['roles'] = array_column($permission['roles'], 'id');
                }
                $dataPermission[$key][] = [
                    'id'         => $permission['id'],
                    'name'       => $permission['name'],
                    'group'      => $permission['group'],
                    'guard_name' => $permission['guard_name'],
                    'roles'      => $permission['roles']
                ];
            }
        }

        return [
            'permissions' => $dataPermission
        ];
    }

    public function updatePermission($data)
    {
        $permission = Permission::findById($data['permission_id'], 'api');

        $permission->syncRoles($data['role_ids']);

        return $permission;
    }
}
