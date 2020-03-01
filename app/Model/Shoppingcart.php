<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Shoppingcart extends Model
{
    public $timestamps=false;//禁止操作时间
    protected  $fillable=['id','userId','productId','number'];

    //关联商品模型（一对多）
    public function product(){
        return $this->hasOne('App\Model\Product','id','productId');
    }
}
