<?php

namespace App\Http\Controllers\manage;

use App\Http\Requests\manage\roleAddRequest;
use App\Model\Admin;
use App\Model\Permission;
use App\Model\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RoleController extends Controller
{
    /*
     * 管理员权限管理
     */

    /**
     * @OA\get(
     *     path="/manage/role",
     *     operationId="index",
     *     tags={"后台权限集合管理员模块"},
     *     summary="获取全部权限集合管理员",
     *     description="",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function index()
    {
        $data=Role::all();
        if ($data!=null){
            return $this->success("获取数据成功",$data);
        }
        return $this->fail("获取数据失败");
    }


    /**
     * @OA\Post(
     *     path="/manage/role",
     *     operationId="insert",
     *     tags={"后台权限集合管理员模块"},
     *     summary="添加权限集合管理员",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="name",
     *         description="权限集合管理员",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="permissionIds",
     *         description="1,2,3",
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
    public function store(roleAddRequest $request)
    {
        $permissionidsArr=explode(",",$request->permissionIds);
        $permissionIds="";
        $len=count($permissionidsArr);
        $fail="";
        $suc=0;
        for($i=0;$i<$len;$i++){
            $permission=Permission::find($permissionidsArr[$i]);
            if ($permission!=null){
                $permissionIds.=$permissionidsArr[$i].',';
                $suc++;
            }else{
                $fail.=$permissionidsArr[$i].",";
            }

        }
        $permissionIds=substr($permissionIds,0,strlen($permissionIds)-1);
        $fail=substr($fail,0,strlen($fail)-1);
        if ($suc==$len){
            $sta=Role::create([
                "name"=>$request->name,
                "permissionId"=>$permissionIds
            ]);
            if($sta){
                return $this->status("0","添加成功!  ".$fail."等ID非法");
            }
        }
        return $this->fail("添加失败! ".$fail."等ID非法");
    }

    /**
     * @OA\get(
     *     path="/manage/role/{id}",
     *     operationId="getById",
     *     tags={"后台权限集合管理员模块"},
     *     summary="获取指定id的权限集合管理员",
     *     description="后台权限集合管理员模块",
     *         @OA\Parameter(
     *         name="id",
     *         description="IDID",
     *         required=false,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
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
    public function show($id)
    {
        $data=Role::find($id);
        if($data!=null){
            return $this->success("获取数据成功",$data);
        }
        return $this->fail("获取数据失败！非法ID");
    }

    /**
     * @OA\put(
     *     path="/manage/role/{id}",
     *     operationId="update",
     *     tags={"后台权限集合管理员模块"},
     *     summary="修改权限集合管理员信息",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="id",
     *         description="IDID",
     *         required=false,
     *         in="path",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         description="权限集合管理员",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="permissionIds",
     *         description="1,2,3",
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
    public function update($id,Request $request)
    {
        $data=Role::find($id);
        if ($data==null)return $this->fail("获取数据失败！非法ID".$data."11");

        $permissionidsArr=explode(",",$request->permissionIds);
        $permissionIds="";
        $len=count($permissionidsArr);
        $fail="";
        $suc=0;
        for($i=0;$i<$len;$i++){
            $permission=Permission::find($permissionidsArr[$i]);
            if ($permission!=null){
                $permissionIds.=$permissionidsArr[$i].',';
                $suc++;
            }else{
                $fail.=$permissionidsArr[$i].",";
            }
        }
        //去除最后的逗号
        $permissionIds=substr($permissionIds,0,strlen($permissionIds)-1);
        $fail=substr($fail,0,strlen($fail)-1);

        if($suc==$len){
            $sta=$data->update([
                "name"=> $request->name,
                "permissionId"=>$permissionIds
            ]);
            if ($sta)
                return $this->status(0,"修改成功!");
        }
        return $this->fail("修改失败! ".$fail."等ID非法");
    }

    /**
     * @OA\delete(
     *     path="/manage/role/{id}",
     *     operationId="delete",
     *     tags={"后台权限集合管理员模块"},
     *     summary="删除指定id的权限集合管理员",
     *     description="后台权限集合管理员模块",
     *         @OA\Parameter(
     *         name="id",
     *         description="IDID",
     *         required=false,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
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
    public function destroy($id)
    {
        $date=Role::find($id);
        $sta=$date->delete();
        if ($sta>0)
            return $this->status(0,"删除成功");
        return  $this->fail("删除失败！非法ID");
    }

    /**
     * @OA\put(
     *     path="/manage/role/updateAdminRole/{id}/{roleId}",
     *     operationId="updateAdminRole",
     *     tags={"后台权限集合管理员模块"},
     *     summary="修改管理员权限集合",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="id",
     *         description="管理员Id",
     *         required=false,
     *         in="path",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="roleId",
     *         description="权限集合管理员",
     *         required=false,
     *         in="path",
     *         @OA\Schema(
     *             type="int"
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
    function  updateAdminRole($id,$roleId){
        $admin=Admin::find($id);
        if ($admin==null)return $this->fail("修改失败！ 非法ID");
        $date=Role::find($roleId);
        if($date==null)return $this->fail("修改失败 非法roleID");
        $sta=$admin->update([
            "roleId"=>$roleId
        ]);
        if($sta)return $this->status(0,"修改成功!");
        return $this->fail("修改失败");
    }

//    function test(){
//        $p1=bcrypt("123456");
//        $p2=Hash::make("123456");
//        //dd("p1:".$p1."       p2:".$p2);
//        $sta=Hash::check("123456",$p2);
//        if ($sta){
//                echo '密码正确'.$p2."     ".$sta;
//        }else{
//            echo '密码错误';
//        }
//
//    }
}
