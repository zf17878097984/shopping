<?php

namespace App\Http\Controllers\manage;

use App\Http\Requests\adminRegisterRequest;
use App\Model\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class adminLoginController extends Controller
{
    /**
     * @OA\get(
     *     path="/manage/login",
     *     operationId="login",
     *     tags={"后台用户登录注册模块"},
     *     summary="用户登录",
     *     description="用户登录",
     *     @OA\Parameter(
     *         name="name",
     *         description="用户名",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="password",
     *         description="密码",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function login(Request $request){
        $admin=Admin::where("name",'=',$request->name)->where('password','=',$request->password)->get();
        if (!$admin->isEmpty()){
            session(['admin' => $admin]);
            Redis::set("admin",$admin);
            return $this->status(0,'登录成功');
        }
        return $this->fail("密码或账户名错误");
    }

    /**
     * @OA\get(
     *     path="/manage/loginOut",
     *     operationId="loginOut",
     *     tags={"后台用户登录注册模块"},
     *     summary="用户注销",
     *     description="用户注销",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function loginOut(Request $request){
        $request->session()->forget("admin");
        return $this->status(0,'注销成功');
    }


    /**
     *
     * @OA\post(
     *     path="/manage/register",
     *     operationId="insert",
     *     tags={"后台用户登录注册模块"},
     *     summary="用户注册",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="name",
     *         description="名字",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *        @OA\Parameter(
     *         name="password",
     *         description="密码",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *         @OA\Parameter(
     *         name="email",
     *         description="邮件",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         request="is_completed",
     *         required=true,
     *         description="If the task is completed or not",
     *         @OA\Schema(
     *             type="boolean"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="The task item created",
     *         @OA\JsonContent()
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function register(adminRegisterRequest $request){
        $sta=Admin::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password
        ]);
        if ($sta){
            return $this->status(0,'恭喜您注册成功');
        }
        return $this->fail("注册失败");
    }
}
