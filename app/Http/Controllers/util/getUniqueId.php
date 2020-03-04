<?php

namespace App\Http\Controllers\util;

use Illuminate\Http\Request;

class getUniqueId
{
    public function genRequestSn($len=0){
        $int = '';

        while (strlen($int) != $len) {
            $int .= mt_rand(0, 9);
        }
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) . $int;
    }
}
