<?php
// 檔案位置：app/Http/middleware/AuthUserAdminMiddleware.php
namespace App\Http\Middleware;
use App\Entity\User;
use Closure;

class AuthUserAdminMiddleware
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

        // 取得會員編號
        $user_id = session()->get('user_id');

        if(!is_null($user_id)){
            // session有會員編號，查看是否為管理員
            $user = User::findOrfail($user_id);

            if($user->type == "A"){
                // 是管理者，允許存取
                $is_allow_access = true;
            }
        }

        if(!$is_allow_access){
            // 若不允許存取，重新導向至首頁
            return redirect('/');
        }

        // 允許存取
        return $next($request);
    }
}
