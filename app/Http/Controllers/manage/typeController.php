<?php

namespace App\Http\Controllers\manage;

use App\Model\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class typeController extends Controller
{

    /**
     * @OA\get(
     *     path="/manage/type",
     *     operationId="getAll",
     *     tags={"后台商品分类模块"},
     *     summary="获取全部商品分类信息",
     *     description="商品分类模块",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function getAll(){
        $data=Type::all();
        if ($data!=null){
            return $this->success("查询成功！",$data);
        }
        return $this->fail("查询失败");
    }



    /**
     * @OA\get(
     *     path="/manage/type/{id}",
     *     operationId="get",
     *     tags={"后台商品分类模块"},
     *     summary="获取指定id的商品分类",
     *     description="商品分类模块",
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
    public function getById($id){
        $data=Type::find($id);
        if ($data!=null){
            return $this->success("查询成功",$data);
        }
        return $this->fail("未查询到该分类");
    }


    /**
     * @OA\post(
     *     path="/manage/type",
     *     operationId="post",
     *     tags={"后台商品分类模块"},
     *     summary="添加商品分类",
     *     description="商品分类模块",
     *     @OA\Parameter(
     *         name="name",
     *         description="商品分类名称",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="describe",
     *         description="商品分类描述",
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
    public function insert(Request $request){
        $sta=Type::create([
            'name'=>$request->name,
            'describe'=>$request->describe
        ]);
        if ($sta){
            return $this->statusCode('0','添加成功！');
        }
        return $this->fail('添加失败！'.$sta);
    }


    /**
     * @OA\Put(
     *     path="/manage/type",
     *     operationId="update",
     *     tags={"后台商品分类模块"},
     *     summary="修改商品分类",
     *     description="商品分类模块",
     *     @OA\Parameter(
     *         name="id",
     *         description="idid",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         description="商品分类名称",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="describe",
     *         description="商品分类描述",
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
    public function update(Request $request){
        $type=Type::find($request->id);
        $sta=$type->update([
            'name'=>$request->name,
            'describe'=>$request->describe
        ]);
        if ($sta){
            return $this->status("0","修改成功！");
        }
        return $this->fail("修改失败");
    }

    /**
     * @OA\delete(
     *     path="/manage/type/{id}",
     *     operationId="delete",
     *     tags={"后台商品分类模块"},
     *     summary="删除指定id的分类",
     *     description="商品分类模块",
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
    public function delete($id)
    {
        $data=Type::find($id);
        if ($data!=null){
            $data=$data->delete();
            return $this->status(0,'删除成功！');
        }else{
            return $this->fail('删除失败，没有该分类');
        }
    }

}
