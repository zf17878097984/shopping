<?php

namespace App\Http\Controllers\shopping;

use App\Http\Requests\AddressAddRequest;
use App\Model\Address;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class addressController extends Controller
{
    /**
     * @OA\get(
     *     path="/shopping/address/getByUserId",
     *     operationId="getByUserId",
     *     tags={"前台收货地址模块"},
     *     summary="获取登录账户的地址信息",
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
    public function getByUserId(){
        $user=session('user');
        $data=Address::where('userId','=',$user[0]->id)->get();
        if (!$data->isEmpty()){
            return $this->success('获取数据成功',$data);
        }
        return $this->fail('您还有没添加过地址');
    }

    /**
     * @OA\get(
     *     path="/shopping/address/{id}",
     *     operationId="getById",
     *     tags={"前台收货地址模块"},
     *     summary="获取指定id的地址信息",
     *     description="收货地址模块",
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
        $data=Address::find($id);
        if($data!=null){
            return $this->success('获取数据成功',$data);
        }
        return $this->fail('获取数据失败');
    }

    /**
     * @OA\post(
     *     path="/shopping/address",
     *     operationId="insert",
     *     tags={"前台收货地址模块"},
     *     summary="添加地址",
     *     description="收货地址模块",
     *     @OA\Parameter(
     *         name="name",
     *         description="收件人姓名",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *         @OA\Parameter(
     *         name="tel",
     *         description="电话号码",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *         @OA\Parameter(
     *         name="address",
     *         description="地址",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
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
    public function insert(AddressAddRequest $request){
        $user=session('user');
        if($user!=null){
        $address=Address::create([
            'name'=>$request->name,
            'tel'=>$request->tel,
            'address'=>$request->address,
            'userId'=>$user[0]->id
        ]);
        if ($address){
            return $this->status(0,'添加成功');
        }
        }
        return $this->fail('添加失败，请重新添加');
    }


    /**
     * @OA\put(
     *     path="/shopping/address",
     *     operationId="update",
     *     tags={"前台收货地址模块"},
     *     summary="修改指定id的地址",
     *     description="收货地址模块",
     *      @OA\Parameter(
     *         name="id",
     *         description="IDID",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         description="收件人姓名",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *         @OA\Parameter(
     *         name="tel",
     *         description="电话号码",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *         @OA\Parameter(
     *         name="address",
     *         description="地址",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="string"
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
    public function update(AddressAddRequest $request){
        $address=Address::find($request->id);
        if ($address!=null){
            $sta=$address->update([
                'name'=>$request->name,
                'tel'=>$request->tel,
                'address'=>$request->address
            ]);
            if ($sta){
                return $this->status(0,'修改成功');
            }
        }
        return $this->fail("非法id,请重新提交");
    }

    /**
     * @OA\delete(
     *     path="/shopping/address/{id}",
     *     operationId="delete",
     *     tags={"前台收货地址模块"},
     *     summary="删除指定id的地址信息",
     *     description="收货地址模块",
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
    public function delete($id){
        $data=Address::find($id);
        if ($data!=null){
            $sta=$data->delete();
            return $this->status(0,'删除数据成功');
        }
        return $this->fail('非法Id,请重新提交');
    }

}
