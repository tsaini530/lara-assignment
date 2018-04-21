<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Product extends FormRequest
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
            'category'  =>  'required|string|max:200',
            'product'   =>  'required|string|max:200',
            'discount'  =>  'required|numeric|between:0,100',
            'price'     =>  'required|numeric|regex:/^\d*(\.\d{1,2})?$/'
        ];
    }
}
