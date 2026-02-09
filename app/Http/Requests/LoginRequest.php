<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            "user" => ['required', 'max:40'],
            "password" =>  ['required', 'min:3', 'max:40'],
        ];
    }

    /**
     * 
     */
    
    public function messages()
    {
        return [
            "user.required" => "O Usuário não pode ser vazio.",
            "user.max" => "O Usuário não pode conter mais de carracteres.",
            "password.required" => "A senha não pode estar vazio!",
            "password.max" => "A Senha não pode conter mais de :max carracteres.",
            "password.min" => "A Senha não pode conter menos de :min carracteres.",
        ];
    }
}
