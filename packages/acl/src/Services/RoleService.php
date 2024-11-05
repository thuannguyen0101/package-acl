<?php

namespace Workable\ACL\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Workable\ACL\Enums\ResponseMessageEnum;
use Workable\ACL\Models\UserApi;

class RoleService
{
    public function __construct()
    {

    }

    public function getRoles(array $filters)
    {
        return $this->buildQuery($filters)->get();
    }

    public function createRole($data): array
    {
        $permissions = [];
        if ($data['permission_ids'] ?? false) {
            $permissions = Permission::query()->whereIn('id', $data['permission_ids'])->pluck('id')->toArray();
            if (count($permissions) < count($data['permission_ids'])) {
                return [
                    'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                    'message' => 'Không tìm thấy quyền.',
                    'role'    => null
                ];
            }
        }

        $data['guard_name'] = 'api';

        $role = Role::create([
            'name'       => $data['name'],
            'guard_name' => $data['guard_name'],
        ]);

        if (!empty($permissions)) {
            $role->syncPermissions($data['permission_ids']);
        }

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => null,
            'role'    => $role
        ];
    }

    public function getRole($id)
    {
        return Role::query()->find($id);
    }

    private function defaultResNotFound(): array
    {
        return [
            'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
            'message' => 'Không tìm thấy vai trò.',
            'role'    => null
        ];
    }

    public function updateRole($id, $data): array
    {
        $role = Role::query()->find($id);

        if (!$role) {
            return $this->defaultResNotFound();
        }

        if (!empty($data['name']) && $data['name'] !== $role->name) {
            $role->update(['name' => $data['name']]);
        }

        if (!empty($data['permission_ids'])) {
            $permissions = Permission::query()->whereIn('id', $data['permission_ids'])->pluck('id')->toArray();

            if (count($permissions) < count($data['permission_ids'])) {
                return [
                    'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                    'message' => 'Không tìm thấy quyền.',
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
            'message' => null,
            'role'    => $role->load('permissions')
        ];
    }

    public function deleteRole($id): bool
    {
        $role = Role::query()->find($id);

        if (empty($role)) {
            return false;
        }

        $role->syncPermissions([]);

        $role->delete();

        return true;
    }

    public function assignRoleForModel(int $userId, int $roleId): array
    {
        $user = UserApi::query()->find($userId);
        if (empty($user)) {
            return [
                'status'  => ResponseMessageEnum::CODE_NOT_FOUND,
                'message' => "Không tìm thấy người dùng.",
                'data'    => null
            ];
        }
        $role = Role::query()->find($roleId);
        if (empty($role)) {
            return $this->defaultResNotFound();
        }

        $user->assignRole($role);

        return [
            'status'  => ResponseMessageEnum::CODE_OK,
            'message' => null,
            'data'    => $user
        ];
    }

    private function buildQuery(array $filters = []): Builder
    {
        $with = [];

        if (isset($filters['with']['permissions']) && (bool)$filters['with']['permissions']) {
            $with = ['permissions'];
        }

        return Role::query()
            ->with($with);
    }
}
