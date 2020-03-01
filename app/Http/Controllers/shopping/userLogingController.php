<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Model\User;
use Illuminate\Http\Request;

class userLogingController extends Controller
{
    /**
     * @OA\get(
     *     path="/shopping/login",
     *     operationId="login",
     *     tags={"前台用户登录注册模块"},
     *     summary="用户登录",
     *     description="钱包管理模块",
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
        $user=User::where("name",'=',$request->name)->where('password','=',$request->password)->get();
        if (!$user->isEmpty()){
            session(['user' => $user]);
            return $this->status(0,'登录成功');
        }
        return $this->fail("密码或账户名错误");
    }

    /**
     * @OA\get(
     *     path="/shopping/loginOut",
     *     operationId="loginOut",
     *     tags={"前台用户登录注册模块"},
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
        $request->session()->forget("user");
        return $this->status(0,'注销成功');
    }


    /**
     *
     * @OA\post(
     *     path="/shopping/register",
     *     operationId="insert",
     *     tags={"前台用户登录注册模块"},
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
     *         name="tel",
     *         description="电话号码",
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
    public function register(UserRegisterRequest $request){
        $sta=User::create([
            'name'=>$request->name,
            'tel'=>$request->tel,
            'password'=>$request->password
        ]);
        if ($sta){
            return $this->status(0,'恭喜您注册成功');
        }
        return $this->fail("注册失败");
    }

}
