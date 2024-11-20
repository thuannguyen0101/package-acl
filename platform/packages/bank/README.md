


### Bước 1.
-   chay "php artisan jwt:secret"

### Bước 2.
-   thêm middleware

```bash 
    protected $routeMiddleware = [
        ....
        'jwt_auth_check' => \Workable\UserTenant\Http\Middleware\JWTMiddleware::class,
        'tenant_all' => \Workable\UserTenant\Http\Middleware\CheckTenantAllMiddleware::class,
        'tenant_own' => \Workable\UserTenant\Http\Middleware\CheckTenantOwnerMiddleware::class,
    ]; 
```

### Bước 3.
-   chay "php artisan migrate"

### Bước 4.
-   chay phần seeder

```bash 
    php artisan db:seed --class=Workable\\UserTenant\\Database\\Seeders\\PermsSeeder    
```
    
