<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     return $next($request);
    // }
    // public function handle($request, Closure $next)
    // {
    //     try {
    //         $user = JWTAuth::parseToken()->authenticate();
    //     } catch (Exception $e) {
    //         if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
    //             return response()->json(['status' => 'Token is Invalid'], 401);
    //         }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
    //             try{
    //                 $refreshed = JWTAuth::refresh(JWTAuth::getToken());
    //                 $user = JWTAuth::setToken($refreshed)->toUser();
    //                 header('Authorization: Bearer ' . $refreshed);
    //             }catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e){
    //                 return response()->json(['status' => 'Token has been Blacklisted']. 500);

    //             } catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
    //                 return response()->json(['status' => 'Token is Expired'], 401);
    //             }
    //             // 
    //         }else{
    //             return response()->json(['status' => 'Authorization Token not found'], 401);
    //         }
    //     }
    //     return $next($request);
    // }

     public function handle($request, Closure $next){
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $status     = 401;
                $message    = 'This token is invalid. Please Login';
                return response()->json(compact('status','message'),401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                // If the token is expired, then it will be refreshed and added to the headers
                try
                {
                  $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                  $user = JWTAuth::setToken($refreshed)->toUser();
                  $request->headers->set('Authorization','Bearer '.$refreshed);
                }catch (JWTException $e){
                    return response()->json([
                        'code'   => 103,
                        'message' => 'Token cannot be refreshed, please Login again'
                    ]);
                }
            }else{
                $message = 'Authorization Token not found';
                return response()->json(compact('message'), 404);
            }
        }
        return $next($request);
    }
}
