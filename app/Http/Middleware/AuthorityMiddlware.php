<?php

namespace App\Http\Middleware;

use App\Model\Permission;
use Closure;

class AuthorityMiddlware
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
        $sum=0;//记录是否拥有该请求类型的权限数量，如果为1则代表拥有该权限
        $permissionId=session("admin")[0]->role->permissionId;
        $permissionIdArr=explode(",",$permissionId);
        for ($i=0;$i<count($permissionIdArr);$i++){
            $label=Permission::find($permissionIdArr[$i])->label;
            if($request->isMethod($label)){//如果该请求类型等于数据库权限的id
                $sum++;
            }
        }
        if($sum!=1){
            return response()->json([
                'code' => -1,
                'msg' => "您没有权限进行操作"
            ]);
        }
        return $next($request);
    }
}
