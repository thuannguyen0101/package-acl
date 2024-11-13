


### Bước 1.
-   chay "php artisan jwt:secret"

### Bước 2.
- thêm middleware
```bash 
    protected $routeMiddleware = [
        ....
        'jwt_auth_check' => \Workable\UserTenant\Http\Middleware\JWTMiddleware::class,
        'tenant_id_check' => \Workable\UserTenant\Http\Middleware\CheckTenantIdMiddleware::class,
    ]; 
```

### Bước 3.
- chay "php artisan migrate"

### Bước 4.
- 


