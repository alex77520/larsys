<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminCateRequest extends FormRequest
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
            'name' => 'required|max:30',
            'digest' => 'string|max:200|nullable'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute不能为空！',
            'max' => ':attribute过长',
            'string' => ':attribute必须为字符串'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '栏目名称',
            'digest' => '栏目简介'
        ];
    }
}
