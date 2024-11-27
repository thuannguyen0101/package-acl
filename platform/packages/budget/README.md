# Budget Package for Laravel

Gói **Budget** cung cấp các tính năng quản lý ngân sách và chi tiêu trong hệ thống, tích hợp với gói **ACL** để phân
quyền và gói **User Tenant** để quản lý dữ liệu theo `tenant_id`.

## Yêu cầu hệ thống

- PHP >= 7.4
- Laravel >= 7.x
- Phụ thuộc:
    - ACL: Quản lý quyền và vai trò.
    - User Tenant: Quản lý tenant và người dùng.

## Tính năng

- **Account Money**: Quản lý tài khoản tiền.
- **Budget**: Quản lý ngân sách và các khoản chi tiêu.
- **Expense Category**: Quản lý danh mục chi tiêu.
- **Phân quyền**:
    - Sử dụng gói **ACL** để phân quyền truy cập và quản lý ngân sách.
- **Tenant**:
    - Tích hợp với gói **User Tenant** để quản lý dữ liệu theo `tenant_id` và `user_id`.

## Cài đặt

### 1. Cài đặt gói

Sử dụng Composer để cài đặt:

```bash
  composer require workable/budget
```

### 2. Cài đặt các gói phụ thuộc

Gói Budget yêu cầu hai gói phụ thuộc:

- ACL:

```bash
  composer require workable/acl
```

- User Tenant:

```bash
  composer require workable/user-tenant
```

### 3. Chạy Migration

```bash
  php artisan migrate
```

### 4. Seeder dữ liệu permission.

```bash
   php artisan db:seed --class=Workable\\Budget\\Database\\Seeders\\PermsSeeder
```

## Hướng Dẫn Sử Dụng

Gói Budget cung cấp các API để quản lý tài chính, bao gồm quản lý Account Money, Expense Category, và Budget. Dưới đây
là hướng dẫn chi tiết về cách sử dụng.

### 1. Middleware `budget_check`

Middleware này kiểm tra xem tenant hiện tại đã có đầy đủ dữ liệu cần thiết để tạo Budget hay chưa:

- Account Money phải tồn tại (với `tenant_id` của người đăng nhập).
- Expense Category phải tồn tại (với `tenant_id` của người đăng nhập).

Nếu thiếu một trong hai, API sẽ trả về lỗi 403 với thông báo tương ứng.

### 3. API Endpoint

#### 3.1. Account Money

Quản lý khoản quỹ

- GET /account-moneys: Danh sách tài khoản tài chính.
- POST /account-moneys: Tạo tài khoản tài chính.
- GET /account-moneys/{id}: Lấy thông tin tài khoản tài chính.
- POST /account-moneys/{id}: Cập nhật tài khoản tài chính.
- DELETE /account-moneys/{id}: Xóa tài khoản tài chính.

#### 3.2. Expense Category

Quản lý danh mục chi tiêu.

- GET /expense_categories: Danh sách danh mục chi tiêu.
- POST /expense_categories: Tạo danh mục chi tiêu.
- GET /expense_categories/{id}: Lấy thông tin danh mục chi tiêu.
- POST /expense_categories/{id}: Cập nhật danh mục chi tiêu.
- DELETE /expense_categories/{id}: Xóa danh mục chi tiêu.

#### 3.3. Budget

Quản lý thu chi hàng ngày (Budget). Yêu cầu middleware budget_check.

* GET /budgets: Danh sách ngân sách.
* POST /budgets: Tạo ngân sách.
* GET /budgets/{id}: Lấy thông tin ngân sách.
* POST /budgets/{id}: Cập nhật ngân sách.
* DELETE /budgets/{id}: Xóa ngân sách.
