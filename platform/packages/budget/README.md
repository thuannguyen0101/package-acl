# Gói ACL cho Laravel

Gói **ACL** cung cấp giải pháp quản lý phân quyền (roles & permissions) dựa trên `spatie/laravel-permission` trong
Laravel. Đây là hướng dẫn cài đặt, cấu hình và sử dụng.

## Tính năng

- Middleware kiểm tra quyền: `acl_permission`
- Quản lý vai trò (role) và quyền (permission):
    - Gán quyền cho vai trò
    - Gán vai trò cho người dùng
    - Kiểm tra quyền trong các action
- Seeder mở rộng để tạo quyền tùy chỉnh.

## Cài đặt

### 1. Cài đặt gói

Sử dụng Composer để cài đặt:

```bash
  composer require workable/user-tenant
  composer require workable/acl
```

### 2. Chạy Migration

```bash
  php artisan migrate
```

### 3. Seed dữ liệu quyền cơ bản

```bash
  php artisan db:seed --class="Workable\ACL\Database\Seeders\PermsTableSeeder"
```

## Hướng dẫn sử dụng

### 1. Middleware kiểm tra quyền

Để kiểm tra quyền trên các route hoặc controller, sử dụng middleware acl_permission.

- Ví dụ:

```php
$this->middleware('acl_permission:permission_list')->only('index');
```

### 2. Quản lý vai trò và quyền

- Gán quyền cho vai trò:
    - Sử dụng route api.role.store để tạo vai trò mới và gán quyền.
    - Sử dụng route api.role.update để cập nhật vai trò mới và gán quyền.

- Gán vai trò cho người dùng:
    - Sử dụng route api.role.assign-model với danh sách role_ids và model_id của người dùng.

### 3. Mở rộng quyền bằng Seeder

Bạn có thể mở rộng Seeder PermsTableSeeder để thêm quyền tùy chỉnh. Ví dụ:

- Tạo file chứa quyền

```php 
//path 'xxx/src/Database/Seeders/Data/custom_perms.php'    
<?php
return [
    'permissions' => [
        'permission_list'
    ],

    'roles' => [
        'role_list',
        'role_create',
        'role_update',
        'role_show',
        'role_delete',
        'role_assign_model',
    ]
];
 
```

- Tạo file seeder

```php

use Workable\ACL\Database\Seeders\PermsTableSeeder;

class CustomPermsTableSeeder extends PermsTableSeeder
{
    protected $path_config = 'xxx/src/Database/Seeders/Data/custom_perms.php';
    
    public function run()
    {
        parent::run(); // Seed các quyền cơ bản
    }
}

```

