<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table='users';
    public $timestamps=false;
    protected $fillable=['money'];
    protected $visible=['money'];
}
