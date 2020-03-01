<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminRegisterRequest extends FormRequest
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
            'name'=>'required|min:2|max:8|Unique:admins',
            'password'=>'required|min:6|max:16',
            'email'=>'required|email',
        ];
    }
}
