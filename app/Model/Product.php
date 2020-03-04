<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps=false;//禁止操作时间
   protected  $fillable=['id','name','describe','price','imgUrl','number','typeId',"sale"];

   public function type(){
       return $this->hasOne('App\Model\Type','id','typeId');
   }
}
