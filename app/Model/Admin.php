<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public $timestamps=false;//禁止操作时间
    protected $fillable=['name','password','email','avatarUrl'];
    protected $hidden = ['password'];
}
