<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
            'name' => 'required|string|min:4',
            'password' => 'required|string|min:4|confirmed',
            'email' => 'required|email'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '姓名不得为空',
            'name.min' => '姓名长度过短',
            'password.required' => '密码不得为空',
            'password.min' => '密码长度过短',
            'email.email' => '邮箱格式错误'
        ];
    }


}
