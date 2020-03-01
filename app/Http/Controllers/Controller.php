<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($message,$data = [])
    {
        return response()->json([
            'code' =>0,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function fail($message)
    {
        return response()->json([
            'code' => -1,
            'msg' => $message,
        ]);
    }

    public function status($code, $message)
    {
        return response()->json([
            'code' => $code,
            'msg' => $message,
        ]);
    }

    public function statusCode($code, $message,$data = [])
    {
        return response()->json([
            'code' => $code,
            'msg' => $message,
            'data' => $data
        ]);
    }


}
