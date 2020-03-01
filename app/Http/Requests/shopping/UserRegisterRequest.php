<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends BaseRequests
{
    /**
     * Determine if the shopping is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|min:2|max:8|Unique:users',
            'password'=>'required|min:6|max:16',
            'tel'=>'required|regex:/^1[345789][0-9]{9}$/',
        ];
    }
}
