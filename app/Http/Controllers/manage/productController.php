<?php

namespace App\Http\Controllers\manage;

use App\Http\Requests\producAddtRequest;
use App\Model\Product;
use App\Model\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


// url="http://140.143.186.207:8081/api/"
//  url="http://shopping/api/"
/**
 * @OA\Info(
 *     version="3.0",
 *     title="Task Resource OpenApi",
 *     @OA\Contact(
 *         name="小玩意儿",
 *         url="http://140.143.186.207:8081/shopping/public/api/documentation",
 *         email="support@todo.test"
 *     )
 * ),
 * @OA\Server(
 *    url="http://shopping/api/"
 * ),
 * @OA\SecurityScheme(
 *     type="oauth2",
 *     description="Use a global client_id / client_secret and your email / password combo to obtain a token",
 *     name="passport",
 *     in="header",
 *     scheme="http",
 *     securityScheme="passport",
 *     @OA\Flow(
 *         flow="password",
 *         authorizationUrl="/oauth/authorize",
 *         tokenUrl="/oauth/token",
 *         refreshUrl="/oauth/token/refresh",
 *         scopes={}
 *     )
 * )
 */
class productController extends Controller
{

    /**
     * @OA\get(
     *     path="/manage/product",
     *     operationId="index",
     *     tags={"后台商品模块"},
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
     *     path="/manage/product/getByTypeId/{typeId}",
     *     operationId="getByTypeId",
     *     tags={"后台商品模块"},
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
     * @OA\Post(
     *     path="/manage/product",
     *     operationId="insert",
     *     tags={"后台商品模块"},
     *     summary="添加商品信息",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="name",
     *         description="商品名称",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="price",
     *         description="价格",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *        @OA\Parameter(
     *         name="number",
     *         description="库存数量,默认100件",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="describe",
     *         description="商品描述",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="typeId",
     *         description="商品分类",
     *         required=false,
     *         in="query",
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
    public function insert(producAddtRequest $request)
    {
        $type=Type::find($request->typeId);
        if ($type==null&&$request->typeId!=null)
            return $this->fail("该商品分类不存在,请重新选择");
        $number=$request->number;
        if ($number==null)$number=100;
        $sta = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'describe' => $request->describe,
            'number'=>$number,
            'typeId'=>$request->typeId
        ]);
        if ($sta){
            return $this->statusCode('0','添加成功！');
        }
        return $this->fail('添加失败！'.$sta);
    }


    /**
     * @OA\get(
     *     path="/manage/product/{id}",
     *     operationId="getById",
     *     tags={"后台商品模块"},
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
        $data->type=$data->type;
        if ($data!=null){
            return $this->success('查询成功',$data);
        }
        return $this->fail('没有该商品');
    }


    /**
     * @OA\Put(
     *     path="/manage/product",
     *     operationId="update",
     *     tags={"后台商品模块"},
     *     summary="修改商品信息",
     *     description="create new task",
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
     *         description="商品名称",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="price",
     *         description="价格",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *         @OA\Parameter(
     *         name="number",
     *         description="数量库存",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="describe",
     *         description="商品描述",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *         @OA\Parameter(
     *         name="typeId",
     *         description="商品分类,为null时不修改typeId",
     *         required=false,
     *         in="query",
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
    public function update(Request $request)
    {
        $product=Product::find($request->id);
        $type=Type::find($request->typeId);
        if ($type==null&&$request->typeId!=null){
            return $this->fail("没有该商品分类,请重新选择");
        }

        if ($request->typeId==null){
            $typeId=$product->typeId;
        }else{
            $typeId=$request->typeId;
        }

        $sta=$product->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'describe'=>$request->describe,
            'number'=>$request->number,
            'typeId'=>$typeId
        ]);
        if ($sta){
            return $this->status("0","修改成功！");
        }
        return $this->fail("修改失败");
        //
    }


    /**
     * @OA\delete(
     *     path="/manage/product/{id}",
     *     operationId="delete",
     *     tags={"后台商品模块"},
     *     summary="删除指定id的商品",
     *     description="商品管理模块管理模块",
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
        $product=Product::find($id);
        if ($product!=null){
            $data=$product->delete();
            return $this->status(0,'删除成功！');
        }else{
            return $this->fail('删除失败，没有该商品');
        }
    }

    /**
     * @OA\Post(
     *     path="/manage/product/upload/{id}",
     *     operationId="upload",
     *     tags={"后台商品模块"},
     *     summary="上传商品图片",
     *     description="商品管理模块",
     *         @OA\Parameter(
     *         name="id",
     *         description="IDID",
     *         required=false,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="imgUrl",
     *         description="imgUrl",
     *         required=false,
     *         in="path",
     *         @OA\Schema(
     *             type="file"
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
    public function upload(Request $request,$id)
    {
        //获取上传文件
        $file=$request->file('imgUrl');
        //判断上传文件是否成功
        if ($file->isValid()){
            //获取源文件扩展名
            $ext=$file->getClientOriginalExtension();
            //新文件名
            $newfile=md5(time().rand(1000,9999)).'.'.$ext;
            //文件上传的指定路径
            $path=public_path('uploads/productImg');
            //将文件从临时目录移动到设置的位置

            if($file->move($path,$newfile)){
                $repath = $request->url();
                $path=substr($repath,0,strpos($repath, 'api'));
                $address='uploads/productImg/';
                $product= Product::find($id);
                $sta=$product->update([
                    'imgUrl'=>$path.$address.$newfile
                ]);
                if ($sta){
                    return $this->success('上传成功');
                }
            }else{
                return $this->fail('上传失败');
            }
        }else{
            return $this->statusCode(-1,'上传文件无效');
        }

    }
}
