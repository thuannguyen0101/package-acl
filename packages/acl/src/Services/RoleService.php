<?php

namespace Workable\ACL\Services;

use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct()
    {

    }

    public function getRoles()
    {
        return Role::query()->with('permissions')->get();
    }

    public function createRole($data)
    {
        $data['guard_name'] = 'api';

        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        if (isset($data['permission_ids'])) {
            $role->syncPermissions($data['permission_ids']);
        }

        return $role;
    }

    public function show($id)
    {
        return Role::query()->find($id);
    }

    public function updateRole( $data)
    {
        $role = Role::query()->find($data['role_id']);
        if (empty($role)) {
            return false;
        }
        $role->update([
            'name' => $data['name'],
        ]);

        if (isset($data['permission_ids'])) {
            $role->syncPermissions($data['permission_ids']);
        }

        return $role;
    }
    public function deleteRole($id){

    }
}
