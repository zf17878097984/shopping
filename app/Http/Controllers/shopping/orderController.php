<?php

namespace App\Http\Controllers\shopping;

use App\Http\Controllers\util\getUniqueId;
use App\Model\Address;
use App\Model\Order;
use App\Model\Product;
use App\Model;
use App\Model\Shoppingcart;
use App\Model\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;


class orderController extends Controller
{
    /**
     *
     * @OA\post(
     *     path="/shopping/order",
     *     operationId="insert",
     *     tags={"前台订单模块"},
     *     summary="新增订单",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="productIds",
     *         description="productIds",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="addressId",
     *         description="addressId",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="numbers",
     *         description="numbers",
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
    public function addOrder(Request $request){
        $getUniqueId=new getUniqueId;
        $id=$getUniqueId->genRequestSn();
        $productArr=explode(",",$request->productIds);
        $numberArr=explode(",",$request->numbers);
        if (Order::find($id)!=null)$id=$getUniqueId->genRequestSn();
        if (Address::where("userId","=",session("user")[0]->id)->where("id","=",$request->addressId)->get()->isEmpty())return $this->fail("您的地址不存在，请重新提交");
        $money=0;
        $idproduct="";
        $idnumber="";
        for($i=0;$i<count($productArr);$i++){
            $product=Product::find($productArr[$i]);
            if ($product!=null&$numberArr[$i]<$product->number){
                $money+=$product->price*$numberArr[$i];
                $idnumber=$numberArr[$i].','.$idnumber;
                $idproduct=$productArr[$i].','.$idproduct;
                continue;
            }
        }
        $sta=Order::create([
            "id"=>$id,
            "userId"=>Session("user")[0]->id,
            "productId"=>substr($idproduct,0,strlen($idproduct)-1),
            "addressId"=>$request->addressId,
            "number"=>substr($idnumber,0,strlen($idnumber)-1),
            "payMethod"=>"网上支付",
            "money"=>$money,
            "createTime"=>date('Y:m:d H:i:s')
        ]);

        if($sta){
            return $this->success("提交成功,请您在15分钟内付款");
        }
        return $this->fail("提交失败");
    }


    /**
     *
     * @OA\post(
     *     path="/shopping/order/batchAdd",
     *     operationId="insertByShopping",
     *     tags={"前台订单模块"},
     *     summary="从购物车中添加新订单",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="shoppingcartIds",
     *         description="1,2",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="String"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="addressId",
     *         description="addressId",
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
    public function batchAdd(Request $request){
        $getUniqueId=new getUniqueId;
        $id=$getUniqueId->genRequestSn();
        $shoppingCartIds=explode(",",$request->shoppingcartIds);
        if (Order::find($id)!=null)$id=$getUniqueId->genRequestSn();
        if (Address::find($request->addressId)==null)return $this->fail("您的地址不存在，请重新提交");
        $money=0;
        $idproduct="";
        $idnumber="";
        for($i=0;$i<count($shoppingCartIds);$i++){
                $shoppingcart=Shoppingcart::find($shoppingCartIds[$i]);
                $product=Product::find($shoppingcart->productId);
                if($product!=null){
                    $money+=$product->price*$shoppingcart->number;
                    $idnumber=$shoppingcart->number.','.$idnumber;
                    $idproduct=$shoppingcart->productId.','.$idproduct;
                }
               $shoppingcart->delete();
        }
        $sta=Order::create([
            "id"=>$id,
            "userId"=>Session("user")[0]->id,
            "productId"=>substr($idproduct,0,strlen($idproduct)-1),
            "addressId"=>$request->addressId,
            "number"=>substr($idnumber,0,strlen($idnumber)-1),
            "payMethod"=>"网上支付",
            "money"=>$money,
            "createTime"=>date('Y:m:d H:i:s')
        ]);
        if($sta){
            return $this->success("提交成功,请您在15分钟内付款");
        }
        return $this->fail("获取数据失败");
    }
    /**
     *
     * @OA\put(
     *     path="/shopping/order/pay/{id}",
     *     operationId="update",
     *     tags={"前台订单模块"},
     *     summary="下单",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="id",
     *         description="IDID",
     *         required=false,
     *         in="path",
     *         @OA\Schema(
     *             type="string"
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
    public function pay($id){
        $order=Order::find($id);
        if ($order==null)return $this->fail("该订单号不存在");
        if ($order->payStatus)return $this->fail("您已经付款成功,请勿重复提交");
        if ((new Carbon)->diffInSeconds (carbon::parse ($order->createTime), false)<60*15){
           $userwallet=Wallet::find(session("user")[0]->id);
            if ($userwallet->money>=$order->money){
                $numberArr=explode(",",$order->number);
                $productArr=explode(",",$order->productId);

                for($i=0;$i<count($numberArr);$i++){
                    $product=Product::find($productArr[$i]);
                    if ($product!=null&$numberArr[$i]<=$product->number){
                        $product->update([
                            "sale"=>$numberArr[$i]+$product->sale,
                            "number"=>$product->number-$numberArr[$i]
                        ]);
                        continue;
                    }
                }

                $sta=$userwallet->update([
                    "money"=>$userwallet->money-$order->money
                ]);
                $order->update([
                    "payStatus"=>true,
                    "payTime"=>date('Y:m:d H:i:s')
                ]);
                if($sta) return $this->status(0,"购买成功");
            }
            return $this->fail("您的余额不足");
        }
        return $this->fail("已经超过付款时间，请重新提交");
    }

    /**
     * @OA\get(
     *     path="/shopping/order",
     *     operationId="getOrder11",
     *     tags={"前台订单模块"},
     *     summary="获取登录用户的订单",
     *     description="前台订单模块",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function getOrder(){
        $user=Session("user");
        $order=Order::where("userId","=",$user[0]->id)->get();
        foreach ($order as $val){
            $productArr=explode(",",$val->productId);
            $numberArr=explode(",",$val->number);

            $val->address=$val->address;
            for ($i=0;$i<count($productArr);$i++){
                $arr[$i]=Product::find($productArr[$i]);
                $arr[$i]->buyNumber=$numberArr[$i];
                $val->product=$arr;
            }
        }

        if ($order!=null){
            return $this->success("获取数据成功",$order);
        }
        return $this->fail("您还没有提交过订单");
    }
}
