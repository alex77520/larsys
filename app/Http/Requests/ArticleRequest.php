<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'title' => 'required|string|min:3',
            'author' => 'required|string',
            'comefrom' => 'string|nullable',
            'tag' => 'string|nullable',
            'digest' => 'string|nullable'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute不能为空',
            'string' => ':attribute必须为字符串',
            'title.min' => '标题长度不能少于三个字符'
        ];
    }

    public function attributes()
    {
        return [
            'title' => '文章标题',
            'author' => '作者',
            'comefrom' => '来源',
            'tag' => '标签',
            'digest' => '文章简介'
        ];
    }
}
