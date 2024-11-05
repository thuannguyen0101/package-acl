<?php

namespace Workable\ACL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Workable\ACL\Enums\UserEnum;

class PermsTableSeeder extends Seeder
{
    protected $path_config;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info('-- Bắt đầu chạy seeder: ' . static::class);
        if (!property_exists($this, 'path_config')) {
            Log::warning('Không tìm thấy thuộc tính path_config.');
            return;
        }

        $groupPerms = require base_path('packages' . DIRECTORY_SEPARATOR . $this->path_config);
        if (empty($groupPerms)) {
            Log::warning('Danh sách phân quyền trống.');
            return;
        }
        $guardName = 'api';

        foreach ($groupPerms as $group => $perms) {
            if (empty($perms)) {
                continue;
            }
            $newPermissions      = [];
            $updatedPermissions  = [];

            $existingPermissions = Permission::query()
                ->whereIn('name', $perms)
                ->where('guard_name', $guardName)
                ->get()
                ->keyBy('name');

            foreach ($perms as $perm) {
                if (!isset($existingPermissions[$perm])) {
                    $newPermissions[] = [
                        'group'      => $group,
                        'name'       => $perm,
                        'guard_name' => $guardName,
                    ];
                } elseif ($existingPermissions[$perm]->group !== $group) {
                    $updatedPermissions[] = $existingPermissions[$perm]->id;
                }
            }

            if (!empty($newPermissions)) {
                Permission::query()->insert($newPermissions);
                Log::info(count($newPermissions) . ' quyền mới đã được thêm.');
            }

            if (!empty($updatedPermissions)) {
                Permission::query()->whereIn('id', $updatedPermissions)
                    ->update(['group' => $group]);
                Log::info(count($updatedPermissions) . ' quyền đã được cập nhật nhóm.');
            }
        }

        Log::info('-- Seeder đã chạy xong: ' . static::class);
    }
}
