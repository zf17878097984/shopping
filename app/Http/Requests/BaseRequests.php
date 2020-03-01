<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequests extends FormRequest
{
    /**
     * 验证失败处理
     *
     */
    public function failedValidation($validator)
    {
        $error = $validator->errors()->first();
        // $allErrors = $validator->errors()->all(); 所有错误

        $response = response()->json([
            'code' => -1,
            'msg'  => $error,
        ]);
        throw new HttpResponseException($response);
    }
}
