<?php

namespace App\Http\Controllers\shopping;

use App\Http\Requests\MoneyRequest;
use App\Model\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class walletController extends Controller
{

    /**
     * @OA\get(
     *     path="/shopping/wallet",
     *     operationId="getWalletByUserId",
     *     tags={"前台钱包模块"},
     *     summary="获取登录用户的钱包信息",
     *     description="钱包管理模块",
     *     @OA\Response(
     *         response=200,
     *         description="The result of tasks"
     *     ),
     *     security={
     *         {"passport": {}},
     *     }
     * )
     */
    public function getWalletByUserId(){
        $user=session('user');
        $data=Wallet::find($user[0]->id);
        return $this->success("获取钱包信息成功",$data);
    }


    /**
     * @OA\put(
     *     path="/shopping/wallet/addCredit",
     *     operationId="insert",
     *     tags={"前台钱包模块"},
     *     summary="充值钱包",
     *     description="create new task",
     *     @OA\Parameter(
     *         name="money",
     *         description="金额",
     *         required=false,
     *         in="query",
     *       @OA\Schema(
     *             type="decimal"
     *         )
     *     ),
     *       @OA\Parameter(
     *         name="rechargeCode",
     *         description="充值码",
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
    public function addCredit(MoneyRequest $request){
        $userId=session('user');
        $user=Wallet::find($userId[0]->id);
        $oldMoney=$user->money;
        $newMoney=$request->money;
        $sum=$oldMoney+$newMoney;

        if($request->rechargeCode==6666){
            $data=$user->update([
                'money'=>$sum
            ]);
        if ($data){
            return $this->status(0,'充值成功');
        }
        }
        return $this->fail("充值码无效,请重新输入");
    }
}
