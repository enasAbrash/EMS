<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
class CheckToken
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!$token = $request->bearerToken()) {
                return  $this->errorResponse('Unauthorized: Missing access token',404);
            }
            $user = JWTAuth::parseToken($token)->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->errorResponse('Unauthorized: Token has expired',401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->errorResponse('Unauthorized: Invalid token',401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return $this->errorResponse('Unauthorized: Token could not be parsed',401);
            } else {
                return $this->errorResponse('Unauthorized',401);
            }
        }
        return $next($request);
    }
}
