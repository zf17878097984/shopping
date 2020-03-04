<?php

namespace App\Http\Controllers\manage;


use App\Http\Requests\manage\adminUpdateRequest;
use App\Http\Requests\shopping\UserUpdateRequest;
use App\Model\Admin;
use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class adminController extends Controller
{
    /**
 * @OA\get(
 *     path="/admin",
 *     operationId="getAdminSession",
 *     tags={"后台用户信息模块"},
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
    public function getAdminSession(){
        $data=session('admin');
        if ($data!=null){
            return $this->success("获取登录信息成功",$data);
        }
        return $this->fail("您还未登录");
    }

    /**
     * @OA\Put(
     *     path="/user",
     *     operationId="updateUser",
     *     tags={"前台用户信息模块"},
     *     summary="修改用户信息",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="name",
     *         description="用户名",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
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
    public function updateUser(UserUpdateRequest $request){
        $data=session('user');
        if ($data!=null){
            $user=User::find($data[0]->id);
            $sta=$user->update([
                "name"=>$request->name,
                "tel"=>$request->tel,
            ]);
            if ($sta){
                session(["user"=>User::find($data[0]->id)]);
                return $this->status(0,"修改成功");
            }
        }
        return $this->fail("您还未登录");
    }

    /**
     * @OA\Put(
     *     path="/admin",
     *     operationId="updateAdmin",
     *     tags={"后台用户信息模块"},
     *     summary="修改用户信息",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="name",
     *         description="用户名",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="email",
     *         description="email",
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
    public function updateAdmin(adminUpdateRequest $request){
        $data=session('admin');
        if ($data!=null){
            $admin=Admin::find($data[0]->id);
            $sta=$admin->update([
                "name"=>$request->name,
                "email"=>$request->email
            ]);
            if ($sta){
                $admin=Admin::find($data['id']);
                $da=session(['admin'=>$admin]);
                return $this->status(0,"修改成功"+$da);
            }
        }
        return $this->fail("您还未登录");
    }

    /**
     * @OA\get(
     *     path="/user",
     *     operationId="getUsersSession",
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
    public function getUsersSession(){
        $data=session('user');
        if ($data!=null){
            return $this->success("获取登录信息成功",$data);
        }
        return $this->fail("您还未登录");
    }
}
