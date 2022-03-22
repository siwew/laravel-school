<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
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
            'email' => 'required|email|unique:teachers',
            'password' => 'required|min:6',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => '名字不能为空',
            'email.required'  => '邮箱不能为空',
            'email.email'  => '邮箱格式错误',
            'email.unique'  => '邮箱已存在',
            'password.min'  => '密码长度不能小于6位',
        ];
    }
}
