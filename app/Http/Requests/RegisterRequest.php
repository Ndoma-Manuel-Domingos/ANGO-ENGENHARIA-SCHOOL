<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            "nome" => ['required', 'max:140'],
            "documento" => ['required', 'max:140'],
            "categoria" => ['required'],
            "natureza" => ['required'],
            "user" => ['required', 'max:40'],
            "password" =>  ['required', 'min:3', 'max:40'],
            "password2" =>  ['required', 'min:3', 'max:40'],
        ];
    }

    /**
     * 
     */
    
    public function messages()
    {
        return [
            "nome.required" => "O nome da Escola não pode ser vazio.",
            "categoria.required" => "A categoria da Escola não pode ser vazio.",
            "natureza.required" => "A natureza da Escola não pode ser vazio.",
            "documento.required" => "O documento não pode estar vazio.",
            "documento.max" => "O o documento não pode conter mais de carracteres.",
            "user.required" => "O Usuário não pode ser vazio.",
            "user.max" => "O Usuário não pode conter mais de carracteres.",
            "password.required" => "A senha não pode estar vazio!",
            "password.max" => "A Senha não pode conter mais de :max carracteres.",
            "password.min" => "A Senha não pode conter menos de :min carracteres.",
            "password2.required" => "O campo confirmar senha não pode estar vazio!",
            "password2.max" => "O compo Confirmar senha não pode conter mais de :max carracteres.",
            "password2.min" => "O compo Confirmar senha não pode conter menos de :min carracteres.",
        ];
    }
}
