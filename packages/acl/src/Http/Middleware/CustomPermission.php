<?php

namespace Workable\ACL\Http\Middleware;

use Closure;

use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CustomPermission
{
    public function handle(Request $request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);
        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        $userPermissions = $authGuard->user()->getAllPermissions()->pluck('name')->toArray();

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Bạn không có quyền thực hiện hành động này.'], 403);
    }
}
