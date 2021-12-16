<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductInfoRequest extends FormRequest
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
            'name' => 'required|min:6|max:255',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên sản phẩm',
            'name.min' => 'Tên sản phẩm phải lớn hơn 5 ký tự',
            'name.max' => 'Tên sản phẩm phải nhỏ hơn 255 ký tự',
            'price.required' => 'Giá bán không được để trống',
            'price.min' => 'Giá bán không được nhỏ hơn 0',
            'price.max' => 'Giá bán không được lớn hơn 99999999',
            'price.numeric' => 'Giá bán chỉ được nhập số',
        ];
    }
}
