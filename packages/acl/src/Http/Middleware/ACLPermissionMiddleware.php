<?php

namespace Workable\ACL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ACLPermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            return response()->json([
                'status'  => 'error',
                'message' => __('acl::api.unauthorized'),
            ], 401);
        }

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ($authGuard->user()->can($permission)) {
                return $next($request);
            }
        }

        return response()->json([
            'status'  => 'error',
            'message' => __('acl::api.forbidden'),
        ], 403);

    }
}
