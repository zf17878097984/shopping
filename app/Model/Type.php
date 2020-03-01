<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps=false;//禁止操作时间
    protected  $fillable=['id','name','describe'];
}
