<?php

namespace Workable\ACL\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\ACL\Models\User;
use Workable\Support\Traits\CheckPermissionTrait;
use Workable\Support\Traits\FilterBuilderTrait;

class RoleService
{
    use FilterBuilderTrait, CheckPermissionTrait;

    public function getRoles(array $searches)
    {
        $filters = $this->getFilterRequest($searches);

        $query = Role::query()
            ->where('tenant_id', get_tenant_id());

        if (!empty($filters['with'])) {
            $query->with($filters['with']);
        }

        $roles = $query->get();

        if ($roles->count() == 0) {
            return [
                'status'  => ResponseMessageEnum::CODE_NO_CONTENT,
                'message' => __('acl::api.no_data'),
                'roles'   => null,
            ];
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.success'),
            'roles'   => $roles,
        ];
    }

    public function getRole($id)
    {
        $role = Role::query()
            ->where('tenant_id', get_tenant_id())
            ->find($id);

        if (!$role) {
            return $this->notFoundResponseDefault();
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.success'),
            'role'    => $role
        ];
    }

    public function createRole($data): array
    {
        $permissions = [];

        if ($data['permission_ids'] ?? false) {
            $permissions = Permission::query()
                ->whereIn('id', $data['permission_ids'])
                ->pluck('id')->toArray();

            if (count($permissions) < count($data['permission_ids'])) {
                return [
                    'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                    'message' => __('acl::api.permission.not_found'),
                    'role'    => null
                ];
            }
        }

        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => 'api',
            'tenant_id'  => get_tenant_id()
        ]);

        if (!empty($permissions)) {
            $role->syncPermissions($data['permission_ids']);
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.created'),
            'role'    => $role
        ];
    }

    public function updateRole($id, $data): array
    {
        $role = Role::query()
            ->where('tenant_id', get_tenant_id())
            ->find($id);

        if (!$role) {
            return $this->notFoundResponseDefault();
        }

        if (!empty($data['name']) && $data['name'] !== $role->name) {
            $role->update(['name' => $data['name']]);
        }

        if (!empty($data['permission_ids'])) {
            $permissions = Permission::query()->whereIn('id', $data['permission_ids'])->pluck('id')->toArray();

            if (count($permissions) < count($data['permission_ids'])) {
                return [
                    'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                    'message' => __('acl::api.permission.not_found'),
                    'role'    => null
                ];
            }

            $role->load('permissions');

            $permissionOld       = $role->permissions->pluck('id')->toArray();
            $permissionsToAdd    = array_diff($data['permission_ids'], $permissionOld);
            $permissionsToRemove = array_diff($permissionOld, $data['permission_ids']);

            if (!empty($permissionsToAdd)) {
                $role->givePermissionTo(array_values($permissionsToAdd));
            }

            if (!empty($permissionsToRemove)) {
                DB::table('role_has_permissions')
                    ->where('role_id', $role->id)
                    ->whereIn('permission_id', $permissionsToRemove)
                    ->delete();
            }

            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.updated'),
            'role'    => $role->load('permissions')
        ];
    }

    public function deleteRole($id)
    {
        $role = Role::query()
            ->where('tenant_id', get_tenant_id())
            ->find($id);

        if (!$role) {
            return $this->notFoundResponseDefault();
        }

        $role->syncPermissions([]);

        $role->delete();

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.deleted'),
        ];
    }

    public function assignRoleForModel(int $userId, int $roleId): array
    {
        $user = User::query()
            ->where('tenant_id', get_tenant_id())
            ->find($userId);

        if (!$user) {
            return [
                'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                'message' => __('acl::api.user.not_found'),
                'user'    => null
            ];
        }

        $role = Role::query()
            ->where('tenant_id', get_tenant_id())
            ->find($roleId);

        if (!$role) {
            return [
                'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                'message' => __('acl::api.role.not_found'),
                'user'    => null
            ];
        }

        $user->assignRole($role);

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => __('acl::api.data_updated'),
            'user'    => $user
        ];
    }

    private function notFoundResponseDefault(): array
    {
        return [
            'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
            'message' => __('acl::api.not_found'),
            'role'    => null
        ];
    }
}
