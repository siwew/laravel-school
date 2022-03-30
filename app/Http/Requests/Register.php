<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;

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

    public function failedValidation(Validator $validator)
    {
        $error = $validator->errors()->first();

        $response = response()->json(['msg'=>$error,'status'=>'error']);

        throw new HttpResponseException($response);
    }
}
