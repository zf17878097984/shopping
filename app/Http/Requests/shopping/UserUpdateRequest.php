<?php

namespace App\Http\Requests\shopping;

use App\Http\Requests\BaseRequests;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends BaseRequests
{
    /**
     * Determine if the user is authorized to make this request.
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
            'name'=>'required|min:2|max:8',
            'tel'=>'required|regex:/^1[345789][0-9]{9}$/',
        ];
    }
}
