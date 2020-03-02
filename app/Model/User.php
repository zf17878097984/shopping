<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps=false;//禁止操作时间
    protected $fillable=['id','name','password','tel','address'];
    protected $hidden = ['money','password'];
}
