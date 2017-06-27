<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPermissionRequest extends FormRequest
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
            'name' => 'required|string',
            'uri' => 'string|nullable',
            'pid' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名称不得为空',
            'name.string' => '名称必须为字符串',
            'uri.string' => '标识必须为字符串，请按照规范填写',
            'pid.required' => '请选择层级'
        ];
    }
}
