<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shoppingcart extends Model
{
    public $timestamps=false;//禁止操作时间
    protected  $fillable=['id','userId','productId','number'];
   // protected $dateFormat = 'U'; //将datetime转为时间戳
    //如果数据库存的是datetime或者没定义$dateFormat，又想取出的时候是int.
//    public function getDates(){
//       return ['created_at','updated_at'];
//    }

    //关联商品模型（一对多）
    public function product(){
        return $this->hasOne('App\Model\Product','id','productId');
    }
}
