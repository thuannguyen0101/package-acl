<?php

namespace Workable\ACL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Workable\ACL\Models\User;

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

        $user = User::find($authGuard->user()->id);

        $permissions = is_array($permission)
            ? $permission
            : explode('|', $permission);

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }

        return response()->json([
            'status'  => 'error',
            'message' => __('acl::api.forbidden'),
        ], 403);

    }
}
