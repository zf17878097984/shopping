<?php

namespace App\Http\Middleware;

use Closure;

class LoginAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!$request->session()->has("admin")){
            return response()->json([
                'code' => -1,
                'msg' => '请您先登录后再进行操作!'
            ]);
        }
        return $next($request);
    }
}
