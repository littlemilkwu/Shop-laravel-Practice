<?php

namespace App\Http\Middleware;

use Closure;

class AuthUserMiddleware
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
        // 預設不允許存取
        $is_allow_access = false;

        // 取得使用者id
        $user_id = session()->get("user_id");

        if(!is_null($user_id)){
            // 有使用者id，允許存取
            $is_allow_access = true;
        }

        if(!$is_allow_access){
            // 不允許存取
            return redirect("/user/auth/sign-in");
        }

        // 允許存取
        return $next($request);
    }
}
