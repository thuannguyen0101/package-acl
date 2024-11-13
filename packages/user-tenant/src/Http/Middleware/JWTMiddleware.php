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

class JWTMiddleware extends BaseMiddleware
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
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => __('user-tenant::api.auth.unauthorized'),
                ], 401);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => __('user-tenant::api.auth.token_expired'),
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => __('user-tenant::api.auth.unauthorized'),
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => __('user-tenant::api.auth.server_error'),
            ], 401);
        }

        return $next($request);
    }
}
