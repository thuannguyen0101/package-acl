<?php

namespace Workable\UserTenant\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Workable\Support\Traits\ResponseHelperTrait;
use Workable\UserTenant\Models\User;

class CheckTenantIdMiddleware extends BaseMiddleware
{
    use ResponseHelperTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!get_tenant_id() || check_tenant_owner()) {
            return response()->json([
                'status'  => 'error',
                'message' => __('user-tenant::api.auth.forbidden'),
            ], 403);
        }

        return $next($request);
    }
}
