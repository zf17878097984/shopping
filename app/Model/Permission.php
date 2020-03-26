<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps=false;//禁止操作时间
    protected $fillable=['name','label'];
}
