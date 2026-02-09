<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormaPagamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_formas_pagamentos";

    protected $fillable = [
        'descricao',
        'sigla_tipo_pagamento',
        'tipo_credito',
    ];
}
