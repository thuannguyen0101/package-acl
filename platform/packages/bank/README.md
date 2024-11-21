# Bank Package for Laravel

Gói **Bank** cung cấp tính năng quản lý tài khoản ngân hàng cho người dùng. Gói này tích hợp hai gói **User Tenant** và
**ACL** để quản lý quyền, vai trò và xác thực dựa trên `tenant_id` và `user_id`.

## Tính năng

- Quản lý tài khoản:
    - Tạo, sửa, xóa tài khoản (`account`).
    - Liên kết tài khoản với người dùng thông qua `user_id` và `tenant_id`.
- Quản lý quyền:
    - Seed thêm quyền cho phần back (sử dụng gói **ACL**).
- Tích hợp với **User Tenant** để xác thực người dùng và tenant.

## Cài đặt

### 1. Cài đặt gói

Sử dụng Composer để cài đặt:

```bash
  composer require workable/bank
```

### 2. Cài đặt các gói phụ thuộc
Gói Bank yêu cầu hai gói phụ thuộc:

- User Tenant:

```bash
  composer require workable/user-tenant 
```
- ACL:

```bash
  composer require workable/acl 
```

### 3. Chạy Migration

```bash
  php artisan migrate
```

### 4. Seed quyền tài khoản
Gói Bank tích hợp với seeder của ACL để thêm các quyền liên quan đến tài khoản. Chạy lệnh sau:

```bash
  php artisan db:seed --class="Workable\ACL\Database\Seeders\PermsTableSeeder"
  php artisan db:seed --class="Workable\Bank\Database\Seeders\BankPermissionSeeder"
```
