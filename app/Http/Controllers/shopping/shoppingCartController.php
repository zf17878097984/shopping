<?php

namespace App\Http\Controllers\shopping;

use App\Model\Product;
use App\Model\Shoppingcart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class shoppingCartController extends Controller
{

    /**
     * @OA\get(
     *     path="/shopping/shoppingCart",
     *     operationId="getAll",
     *     tags={"前台购物车模块"},
     *     summary="获取全部该用户全部购物车信息",
     *     description="购物车模块",
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
        $user=session("user");
        $data=Shoppingcart::where('userId','=',$user[0]->id)->get();
        foreach ($data as $key=>$val){
            $val->product=$val->product;
        }
        if (!$data->isEmpty()){
            return $this->success("查询成功！",$data);
        }
        return $this->fail("查询失败");
    }

    /**
     * @OA\get(
     *     path="/shopping/shoppingCart/getById/{id}",
     *     operationId="get",
     *     tags={"前台购物车模块"},
     *     summary="获取指定id的购物车信息",
     *     description="购物车模块",
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
        $data=Shoppingcart::find($id);
        if ($data!=null){
            $product=Product::find($data->productId);
            $data->product=$product;
            return $this->success("查询成功",$data);
        }
        return $this->fail("未查询到该商品");
    }


    /**
     * @OA\post(
     *     path="/shopping/shoppingCart",
     *     operationId="insert",
     *     tags={"前台购物车模块"},
     *     summary="添加商品到购物车",
     *     description="购物车模块",
     *     @OA\Parameter(
     *         name="productId",
     *         description="商品id",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="int"
     *         )
     *     ),
     *         @OA\Parameter(
     *         name="number",
     *         description="加入购物车的商品数量,默认为1",
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
    public function insert(Request $request){
        $user=session("user");
        $product=Product::find($request->productId);
        if ($product==null){
            return $this->fail('商品过期或不存在');
        }
        if ($request->number==null){
            $number=1;
        }else{
            $number=$request->number;
        }
        $shoppingCart=Shoppingcart::Where("userId","=",$user[0]->id)->where("productId","=",$request->productId)->first();

        if ($shoppingCart==null){
        $sta=Shoppingcart::create([
            'productId'=>$request->productId,
            'userId'=>$user[0]->id,
            'number'=>$number
        ]);
            if ($sta){
                return $this->statusCode('0','添加成功！');
            }
        }else{
            $oldNumber=$shoppingCart->number;
            $staUp=Shoppingcart::Where("userId","=",$user[0]->id)->where("productId","=",$request->productId)->update([
                'number'=>$number+$oldNumber
            ]);
            if ($staUp){
                return $this->statusCode('0','添加成功！');
            }
        }

        return $this->fail($shoppingCart);
    }


    /**
     * @OA\delete(
     *     path="/shopping/shoppingCart/{id}",
     *     operationId="delete",
     *     tags={"前台购物车模块"},
     *     summary="删除指定id的购物车商品",
     *     description="购物车模块",
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
        $data=Shoppingcart::find($id);
        if ($data!=null){
            $data=$data->delete();
            return $this->status(0,'删除成功！');
        }else{
            return $this->fail('删除失败，没有该商品');
        }
    }

    /**
     * @OA\Delete(
     *     path="/shopping/shoppingCart/batchDelete/{ids}",
     *     operationId="batchDelete",
     *     tags={"前台购物车模块"},
     *     summary="批量删除指定id的购物车商品",
     *     description="购物车模块",
     *         @OA\Parameter(
     *         name="ids",
     *         description="1,2,3",
     *         required=false,
     *         in="path",
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
    public function batchDelete($ids){
        //把传来的所有id改为数组形式  explode  字符串转数组
        $arr = explode(",",$ids);
        //利用循环将需要删除的id 一个一个进行执行sql；
        $i=0;
        foreach($arr as $index){
            $sta=Shoppingcart::Where('id',"=",$index)->delete();
            if ($sta>0){
                $i++;
            }
        }
        return $this->status(0,'删除了'.$i.'个购物车商品');
    }

    /**
     * @OA\post(
     *     path="/shopping/shoppingCart/batchInsert",
     *     operationId="batchInsert",
     *     tags={"前台购物车模块"},
     *     summary="批量添加商品到购物车",
     *     description="购物车模块",
     *     @OA\Parameter(
     *         name="productIds",
     *         description="1,2,3",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="numbers",
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
    public  function batchInsert(Request $request){
        $user=session("user");
        $productArr=explode(",",$request->productIds);
        $numberArr=explode(',',$request->numbers);
        $success=0;
        $erro=0;
        for($i=0;$i<count($productArr);$i++){
            $product=Product::find($productArr[$i]);
            if ($product==null){
                $erro++;
                continue;
            }
            $sta=Shoppingcart::create([
                'productId'=>$productArr[$i],
                'number'=>$numberArr[$i],
                'userId'=>$user[0]->id
            ]);
            if ($sta){
                $success++;
            }
        }
        return $this->status(0,"添加了".$success."个商品到购物车,".$erro.'个商品过期或不存在');
    }
}
