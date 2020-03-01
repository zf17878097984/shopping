<?php

namespace App\Http\Controllers\shopping;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class userController extends Controller
{
    /**
     * @OA\get(
     *     path="/shopping/user",
     *     operationId="getUser",
     *     tags={"前台用户信息模块"},
     *     summary="获取登录用户的信息",
     *     description="获取登录用户的信息",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function getUser(){
        $data=session('user');
        if ($data!=null){
            return $this->success("获取登录信息成功",$data);
        }
        return $this->fail("您还未登录");
    }
}
