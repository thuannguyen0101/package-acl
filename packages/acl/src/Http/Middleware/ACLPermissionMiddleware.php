<?php

namespace Workable\ACL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Workable\ACL\Core\Traits\ApiResponseTrait;
use Workable\ACL\Enums\ResponseMessageEnum;

class ACLPermissionMiddleware
{
    use ApiResponseTrait;

    public function handle(Request $request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            return $this->errorResponse("", ResponseMessageEnum::CODE_UNAUTHORIZED);
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ($authGuard->user()->can($permission)) {
                return $next($request);
            }
        }

        return $this->errorResponse("", ResponseMessageEnum::CODE_FORBIDDEN);
    }
}
