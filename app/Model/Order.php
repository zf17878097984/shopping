<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps=false;//禁止操作时间
    protected  $fillable=['id','userId','productId','payMethod','payStatus','money','createTime','number','addressId'];

    public function address(){
        return $this->hasOne('App\Model\Address','id','addressId');
    }

}
