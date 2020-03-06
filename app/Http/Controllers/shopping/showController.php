<?php

namespace App\Http\Controllers\shopping;

use App\Model\Product;
use App\Model\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class showController extends Controller
{
    /**
     * @OA\get(
     *     path="/shopping/product",
     *     operationId="index",
     *     tags={"前台商品展示模块"},
     *     summary="获取全部商品信息",
     *     description="商品管理模块",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function getAll()
    {
        $data=Product::orderBy('id','desc') -> paginate(10);
        foreach ($data as $val){
            $val->type=$val->type;
        }
        return $this->success('获取信息成功！',$data);
    }

    /**
     * @OA\get(
     *     path="/shopping/product/type",
     *     operationId="getTypeAll",
     *     tags={"前台商品展示模块"},
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
    public function getTypeAll(){
        $data=Type::all();
        if ($data!=null){
            return $this->success("查询成功！",$data);
        }
        return $this->fail("查询失败");
    }

    /**
     * @OA\get(
     *     path="/shopping/product/getByTypeId/{typeId}",
     *     operationId="getByTypeId",
     *     tags={"前台商品展示模块"},
     *     summary="获取指定商品分类的商品",
     *     description="",
     *         @OA\Parameter(
     *         name="typeId",
     *         description="typeId",
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
    public function getByTypeId($typeId){
        $data=Product::where('typeId','=',$typeId)->get();
        if (!$data->isEmpty()){
            return $this->success("获取数据成功",$data);
        }
        return $this->fail("获取数据失败");
    }

    /**
     * @OA\get(
     *     path="/shopping/product/getById/{id}",
     *     operationId="getById",
     *     tags={"前台商品展示模块"},
     *     summary="获取指定id的商品",
     *     description="用户管理模块",
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
    public function getById($id)
    {
        $data=Product::find($id);
        if ($data!=null){
            return $this->success('查询成功',$data);
        }
        return $this->fail('没有该商品');
    }

    /**
     * @OA\get(
     *     path="/shopping/product/saleWell",
     *     operationId="saleWell",
     *     tags={"前台商品展示模块"},
     *     summary="获取热销商品",
     *     description="前台商品展示模块",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function saleWell(){
        $data=Product::orderBy("sale","desc")->get();
        if($data!=null){
            return $this->success("获取数据成功",$data);
        }
        return $this->fail("查询失败");
    }

    /**
     * @OA\get(
     *     path="/shopping/product/newest",
     *     operationId="saleWell",
     *     tags={"前台商品展示模块"},
     *     summary="获取最新商品",
     *     description="前台商品展示模块",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function newest(){
        $data=Product::orderBy("createTime","desc")->get();
        if($data!=null){
            return $this->success("获取数据成功",$data);
        }
        return $this->fail("查询失败");
    }
}
