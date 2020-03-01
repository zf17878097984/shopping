<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class producUpdatetRequest extends FormRequest
{
    /**
     * Determine if the shopping is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|min:2|max:20',
            'price'=>'required|numeric',
            'number'=>'numeric',
            'describe'=>'required',
        ];
    }
}
