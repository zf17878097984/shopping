<?php

namespace App\Http\Requests\manage;

use App\Http\Requests\BaseRequests;
use Illuminate\Foundation\Http\FormRequest;

class roleAddRequest extends BaseRequests
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
            "name"=>"required|min:1|max:20|Unique:roles",
            "permissionIds"=>"required"
        ];
    }
}
