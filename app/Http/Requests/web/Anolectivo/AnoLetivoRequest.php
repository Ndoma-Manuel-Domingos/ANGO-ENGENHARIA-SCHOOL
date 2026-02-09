<?php

namespace App\Http\Requests\web\Anolectivo;

use Illuminate\Foundation\Http\FormRequest;

class AnoLetivoRequest extends FormRequest
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
            "ano" => ['required'],
            "inicio" =>  ['required'],
            "final" =>  ['required'],
            "status" =>  ['required'],
        ];
    }

    /**
     * 
     */
    
    public function messages()
    {
        return [
            "ano.required" => "O campo ano lectivo não pode ser vazio.",
            "inicio.required" => "O campo inicio não pode estar vazio!",
            "final.required" => "O final não pode estar vazio!.",
            "status.required" => "O Status não pode estar vazio!.",
        ];
    }
}
