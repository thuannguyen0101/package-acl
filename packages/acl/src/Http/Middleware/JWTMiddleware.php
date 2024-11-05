<?php

namespace Workable\ACL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Workable\ACL\Core\Traits\ApiResponseTrait;

class JWTMiddleware extends BaseMiddleware
{
    use ApiResponseTrait;

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
                return $this->errorResponse("Token không hợp lệ.", 401);
            }
        } catch (TokenExpiredException $e) {
            return $this->errorResponse("Token đã hết hạn.", 401);
        } catch (TokenInvalidException $e) {
            return $this->errorResponse("Token không hợp lệ.", 401);
        } catch (JWTException $e) {
            return $this->errorResponse("Không thể xác thực token.", 401);

        }

        return $next($request);
    }
}
