<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthJwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            if(!JWTAuth::parseToken()->authenticate()){
                return response()->json(['message' => 'unauthorize'], 404);
            }
        }catch(\Exception $e){
            return response()->json(['message' => 'unauthorize'], 502);
        }
        return $next($request);
    }
}
