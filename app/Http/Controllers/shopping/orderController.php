<?php

namespace App\Http\Controllers\shopping;

use App\Http\Controllers\util\getUniqueId;
use App\Model\Address;
use App\Model\Order;
use App\Model\Product;
use App\Model;
use App\Model\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use MongoDB\Driver\Session;

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
        if (Address::find($request->addressId)==null)return $this->fail("您的地址不存在，请重新提交");
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
        return $this->fail("获取数据失败");
    }
    /**
     *
     * @OA\put(
     *     path="/shopping/order/pay",
     *     operationId="update",
     *     tags={"前台订单模块"},
     *     summary="下单",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="id",
     *         description="订单id",
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
    public function pay(Request $request){

        $order=Order::find($request->id);
        if ($order->payStatus)return $this->fail("您已经付款成功,请勿重复提交");
        if ((new Carbon)->diffInSeconds (carbon::parse ($order->createTime), false)<60*15){
           $userwallet=Wallet::find(session("user")[0]->id);
            if ($userwallet->money>$order->money){
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
                    "payStatus"=>true
                ]);
                if($sta) return $this->status(0,"购买成功");
            }
            return $this->fail("您的余额不足");
        }
        return $this->fail("已经超过付款时间，请重新提交");
    }
}
